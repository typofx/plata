<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

include "conexao.php"; // Inclua o arquivo de conexão com o banco de dados

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
if (file_exists('team_members.json')) {
    $json_content = file_get_contents('team_members.json');
    $json_data = json_decode($json_content, true);
} else {
    $json_data = [];
}

// Definir opções de mídias sociais
$socialMediaFields = ['WhatsApp', 'Instagram', 'Telegram', 'Facebook', 'GitHub', 'Email', 'Twitter', 'LinkedIn', 'Twitch', 'Medium'];
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
    <a href="team_members.json">[JSON]</a>
    <br>
    <br>
    <form action="process.php" method="post">
        <label for="num_members">Number of Team Members (1 to 5):</label>
        <select id="num_members" name="num_members" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= $i <= count($json_data) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

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

        function createMemberFields(memberIndex, memberData = {}) {
            let memberOptions = '';

            // Gerando opções de membros dinamicamente com dados PHP
            members.forEach(member => {
                memberOptions += `<option value="${member.teamName}" ${memberData.name == member.teamName ? 'selected' : ''}>${member.teamName}</option>`;
            });

            let selectedSocialMedia = memberData.social_media || {};

            membersContainer.innerHTML += `
                <fieldset>
                    <legend>Member ${memberIndex}</legend>
                    <label for="member_${memberIndex}">Select Member:</label>
                    <select id="member_${memberIndex}" name="members[${memberIndex}][teamName]" required>
                        ${memberOptions}
                    </select>
                    
                    <label>Social Media (Choose up to 3):</label>
                    <select name="members[${memberIndex}][social_media][]" multiple required>
                        ${socialMediaFields.map(field => `
                            <option value="${field}" ${selectedSocialMedia[field] ? 'selected' : ''}>${field}</option>
                        `).join('')}
                    </select>

                    <a href="remove_member.php?remove_member=${memberIndex}">Remove</a>
                </fieldset>
            `;
        }

        numMembersSelect.addEventListener('change', function() {
            membersContainer.innerHTML = '';
            const numMembers = this.value;

            for (let i = 1; i <= numMembers; i++) {
                // Verificar se há dados salvos para este membroIndex no JSON
                if (savedData[i - 1] && savedData[i - 1].name) {
                    // Se houver, criar campos preenchidos com os dados salvos
                    createMemberFields(i, savedData[i - 1]);
                } else {
                    // Se não houver dados salvos, criar campos vazios
                    createMemberFields(i);
                }
            }
        });

        // Trigger change event to load the initial set of fields
        numMembersSelect.dispatchEvent(new Event('change'));
    </script>
</body>

</html>
