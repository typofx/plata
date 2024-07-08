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
        $members[$row['id']] = $row; // Usar o id como chave no array
    }
}
$conn->close();

// Carregar dados do JSON se existir
$json_data = [];
if (file_exists('team_members.json')) {
    $json_content = file_get_contents('team_members.json');
    $json_data = json_decode($json_content, true);
}

// Processar a remoção de um membro
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['remove_member'])) {
    $indexToRemove = $_GET['remove_member'];
    if (isset($json_data[$indexToRemove])) {
        unset($json_data[$indexToRemove]);
        // Reindexar o array após a remoção
        $json_data = array_values($json_data);
        
        // Salvar de volta no arquivo JSON
        file_put_contents('team_members.json', json_encode($json_data, JSON_PRETTY_PRINT));
        
        // Redirecionar de volta para a página principal após a remoção
        header("Location: form.php");
        exit();
    }
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
    <a href="team_members.json">[JSON]</a>
    <br>
    <br>
    <form action="process.php" method="post">
        <label for="num_members">Number of Team Members (1 to 5):</label>
        <select id="num_members" name="num_members" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= isset($json_data[$i - 1]) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <div id="members_container"></div>

        <button type="submit">Save to JSON</button>
    </form>

    <script>
        const members = <?= json_encode($members) ?>;
        const savedData = <?= json_encode($json_data) ?>;
        const membersContainer = document.getElementById('members_container');
        const numMembersSelect = document.getElementById('num_members');

        function createMemberFields(memberIndex, memberData = {}) {
            let memberOptions = '';
            Object.keys(members).forEach(memberId => {
                const member = members[memberId];
                memberOptions += `<option value="${member.id}" ${memberData.id == memberId ? 'selected' : ''}>${member.teamName}</option>`;
            });

            let selectedSocialMedia = memberData.social_media || {};

            let selectedMemberId = memberData.id || ''; // Definir selectedMemberId

            membersContainer.innerHTML += `
                <fieldset>
                    <legend>Member ${memberIndex}</legend>
                    <label for="member_${memberIndex}">Select Member:</label>
                    <select id="member_${memberIndex}" name="members[${memberIndex}][id]" required>
                        ${memberOptions}
                    </select>
                    
                    <label>Social Media (Choose up to 3):</label>
                    <select name="members[${memberIndex}][social_media][]" multiple required>
                        <option value="teamSocialMedia0" ${selectedSocialMedia.teamSocialMedia0 ? 'selected' : ''}>WhatsApp</option>
                        <option value="teamSocialMedia1" ${selectedSocialMedia.teamSocialMedia1 ? 'selected' : ''}>Instagram</option>
                        <option value="teamSocialMedia2" ${selectedSocialMedia.teamSocialMedia2 ? 'selected' : ''}>Telegram</option>
                        <option value="teamSocialMedia3" ${selectedSocialMedia.teamSocialMedia3 ? 'selected' : ''}>Facebook</option>
                        <option value="teamSocialMedia4" ${selectedSocialMedia.teamSocialMedia4 ? 'selected' : ''}>GitHub</option>
                        <option value="teamSocialMedia5" ${selectedSocialMedia.teamSocialMedia5 ? 'selected' : ''}>Email</option>
                        <option value="teamSocialMedia6" ${selectedSocialMedia.teamSocialMedia6 ? 'selected' : ''}>Twitter</option>
                        <option value="teamSocialMedia7" ${selectedSocialMedia.teamSocialMedia7 ? 'selected' : ''}>LinkedIn</option>
                        <option value="teamSocialMedia8" ${selectedSocialMedia.teamSocialMedia8 ? 'selected' : ''}>Twitch</option>
                        <option value="teamSocialMedia9" ${selectedSocialMedia.teamSocialMedia9 ? 'selected' : ''}>Medium</option>
                    </select>

                    <input type="hidden" name="members[${memberIndex}][teamName]" value="${selectedMemberId}">
                    <a href="remove_member.php?remove_member=${selectedMemberId}">Remove</a>
                </fieldset>
            `;
        }

        numMembersSelect.addEventListener('change', function() {
            membersContainer.innerHTML = '';
            const numMembers = this.value;

            for (let i = 1; i <= numMembers; i++) {
                createMemberFields(i, savedData[i - 1]);
            }
        });

        // Trigger change event to load the initial set of fields
        numMembersSelect.dispatchEvent(new Event('change'));
    </script>
</body>

</html>
