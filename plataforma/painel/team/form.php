<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

include "conexao.php"; // Inclui o arquivo de conexão com o banco de dados

// Consulta para obter todos os membros da equipe
$sql = "SELECT * FROM granna80_bdlinks.team";
$result = $conn->query($sql);

$members = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row; // Adicionar o membro ao array
    }
}
$conn->close();

// Carregar dados do JSON se existir
$json_data = [];

$file_path = $_SERVER['DOCUMENT_ROOT'] . '/sandbox/team/team_members.json';

if (file_exists($file_path)) {
    $json_content = file_get_contents($file_path);
    $json_data = json_decode($json_content, true);
} else {
    $json_data = [];
}

// Definir opções de mídias sociais
$socialMediaFields = ['WhatsApp', 'Instagram', 'Telegram', 'Facebook', 'GitHub', 'Email', 'Twitter', 'LinkedIn', 'Twitch', 'Medium'];

// Determinar número de campos de mídias sociais a partir dos dados salvos
$num_social_media = 4; // Padrão para 4
if (!empty($json_data['members'][0]['social_media'])) {
    $num_social_media = count($json_data['members'][0]['social_media']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Team Members</title>
</head>

<body>
    <h2>Select Team Members</h2>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Root Menu]</a>
    <a href="https://plata.ie/plataforma/painel/team/index.php">[Edit Team]</a>
    <a href="https://www.plata.ie/sandbox/team/team_members.json" target="_blank">[JSON]</a>

    <br><br>
    <!-- Select para escolher o número de membros -->
    <form action="process.php" method="post">
        <label for="num_members">Number of Team Members (1 to 5):</label>
        <select id="num_members" name="num_members" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= $i <= count($json_data['members']) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <br><br>

        <!-- Botão para escolher entre 3 ou 4 mídias sociais -->
        <label for="num_social_media">Number of Social Media Fields:</label>
        <select id="num_social_media" name="num_social_media" required>
            <option value="3" <?= $num_social_media == 3 ? 'selected' : '' ?>>3</option>
            <option value="4" <?= $num_social_media == 4 ? 'selected' : '' ?>>4</option>
        </select>

        <!-- Div para conter os campos de membros da equipe -->
        <br><br>
        <div id="members_container"></div>

        <button type="submit">Save to JSON</button>
    </form>

    <script>
        // Passando dados PHP para JavaScript
        const members = <?= json_encode($members) ?>;
        const savedData = <?= json_encode($json_data) ?>;
        const socialMediaFields = <?= json_encode($socialMediaFields) ?>;

        const membersContainer = document.getElementById('members_container');
        const numMembersSelect = document.getElementById('num_members');
        const numSocialMediaSelect = document.getElementById('num_social_media');

        function createMemberFields(memberIndex, memberData = {}, numSocialMedia = 4) {
            let memberOptions = '';

            // Gerando opções de membros dinamicamente com dados PHP
            members.forEach(member => {
                memberOptions += `<option value="${member.teamName}" ${memberData.name == member.teamName ? 'selected' : ''}>${member.teamName}</option>`;
            });

            membersContainer.innerHTML += `
                <fieldset>
                    <legend>Member ${memberIndex}</legend>
                    <label for="member_${memberIndex}">Select Member:</label>
                    <select id="member_${memberIndex}" name="members[${memberIndex}][name]" required>
                        ${memberOptions}
                    </select>
                    
                    <!-- Fixed social media fields -->
                    
                    ${socialMediaFields.slice(0, numSocialMedia).map((field, index) => `
                        <label for="social_media_fixed_select_${memberIndex}_${index + 1}">Social Media ${index + 1}:</label>
                        <select id="social_media_fixed_select_${memberIndex}_${index + 1}" name="members[${memberIndex}][social_media][${index}]" required>
                            <option value="">Select One</option>
                            ${socialMediaFields.map(option => `
                                <option value="${option}" ${memberData.social_media && memberData.social_media[index] && memberData.social_media[index][1] === option ? 'selected' : ''}>${option}</option>
                            `).join('')}
                        </select>
                    
                    `).join('')}

                    <a href="remove_member.php?remove_member=${memberData.name}">[Remove]</a>
                    </fieldset>
                <br><br>
            `;
        }

        function updateMembers() {
            membersContainer.innerHTML = '';
            const numMembers = numMembersSelect.value;
            const numSocialMedia = numSocialMediaSelect.value;

            for (let i = 1; i <= numMembers; i++) {
                // Verificar se há dados salvos para este membroIndex no JSON
                if (savedData.members && savedData.members[i - 1] && savedData.members[i - 1].name) {
                    // Se houver, criar campos preenchidos com os dados salvos
                    createMemberFields(i, savedData.members[i - 1], numSocialMedia);
                } else {
                    // Se não houver dados salvos, criar campos vazios
                    createMemberFields(i, {}, numSocialMedia);
                }
            }
        }

        numMembersSelect.addEventListener('change', updateMembers);
        numSocialMediaSelect.addEventListener('change', updateMembers);

        // Trigger change event to load the initial set of fields
        document.addEventListener('DOMContentLoaded', function() {
            updateMembers();
        });
    </script>
</body>

</html>
