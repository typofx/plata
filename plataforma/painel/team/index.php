<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php

$isGuest = ($userLevel === "guest");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Team Members List</title>
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            height: 100px;
            padding: 0;
            margin: 0;
            line-height: 0;
            border: 1px solid black;
        }

        .vertical-column {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            text-orientation: mixed;
            padding: 0;
            margin: 0;
            width: 20px;
            /* Largura da coluna vertical */
            background-color: transparent;
        }

        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>

    <style>
        .vertical-text {
            font-size: 16px;
            /* Tamanho da fonte */
            width: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Team Members List</h2>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>

    <?php if (!$isGuest) : ?>
        <a href="add.php">[Add new member]</a>
        <a href="form.php">[Set JSON]</a>
        <a href="active.php">[Currently work here]</a>
    <?php endif; ?>
    <br>
    <br>
    <table>
        <tr>
            <th>Profile Picture</th>
            <th>Name</th>
            <th>Position</th>
            <th class="vertical-column">WHATSAPP</th>
            <th class="vertical-column">INSTAGRAM</th>
            <th class="vertical-column">TELEGRAM</th>
            <th class="vertical-column">FACEBOOK</th>
            <th class="vertical-column">GITHUB</th>
            <th class="vertical-column">EMAIL</th>
            <th class="vertical-column">TWITTER</th>
            <th class="vertical-column">LINKEDIN</th>
            <th class="vertical-column">TWITCH</th>
            <th class="vertical-column">MEDIUM</th>
            <th class="vertical-column">DOCS</th>

            <th>Actions</th>
            <th>Work here?</th>
        </tr>

        <?php

        // Include database configuration file
        include "conexao.php";

        // SQL query to select all team members
        $sql = "SELECT * FROM granna80_bdlinks.team where active = 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display each team member in a table row
            while ($row = $result->fetch_assoc()) {
                // Fetch corresponding member_name from team_docs table
                $doc_sql = "SELECT id FROM granna80_bdlinks.team_docs WHERE member_name = '" . $row['teamName'] . "'";
                $doc_result = $conn->query($doc_sql);
                $docs = ($doc_result->num_rows > 0) ? $doc_result->fetch_assoc()['id'] : 'none';

                echo "<tr>";
                echo "<td><img src='/images/" . htmlspecialchars($row['teamProfilePicture']) . "' width='100'></td>";
                echo "<td>" . htmlspecialchars($row['teamName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['teamPosition']) . "</td>";
                echo "<td><a href='https://wa.me/" . htmlspecialchars($row['teamSocialMedia0']) . "' target='_blank'><i class='fa-brands fa-whatsapp' style='color: #25D366;'></i></a></td>";
                echo "<td><a href='https://www.instagram.com/" . htmlspecialchars($row['teamSocialMedia1']) . "' target='_blank'><i class='fa-brands fa-instagram' style='color: #E4405F;'></i></a></td>";
                echo "<td><a href='https://t.me/" . htmlspecialchars($row['teamSocialMedia2']) . "' target='_blank'><i class='fa-brands fa-telegram' style='color: #0088cc;'></i></a></td>";
                echo "<td><a href='https://www.facebook.com/" . htmlspecialchars($row['teamSocialMedia3']) . "' target='_blank'><i class='fa-brands fa-facebook' style='color: #1877F2;'></i></a></td>";
                echo "<td><a href='https://github.com/" . htmlspecialchars($row['teamSocialMedia4']) . "' target='_blank'><i class='fa-brands fa-github' style='color: #333;'></i></a></td>";
                echo "<td><a href='mailto:" . htmlspecialchars($row['teamSocialMedia5']) . "' target='_blank'><i class='fa-solid fa-envelope' style='color: #D44638;'></i></a></td>";
                echo "<td><a href='https://twitter.com/" . htmlspecialchars($row['teamSocialMedia6']) . "' target='_blank'><i class='fa-brands fa-square-x-twitter' style='color: #000;'></i></a></td>";
                echo "<td><a href='https://www.linkedin.com/in/" . htmlspecialchars($row['teamSocialMedia7']) . "' target='_blank'><i class='fa-brands fa-linkedin' style='color: #0077B5;'></i></a></td>";
                echo "<td><a href='https://www.twitch.tv/" . htmlspecialchars($row['teamSocialMedia8']) . "' target='_blank'><i class='fa-brands fa-twitch' style='color: #9146FF;'></i></a></td>";
                echo "<td><a href='https://medium.com/@" . htmlspecialchars($row['teamSocialMedia9']) . "' target='_blank'><i class='fa-brands fa-medium' style='color: #12100E;'></i></a></td>";
                $canEdit = ($userLevel === "guest" && $row['uuid'] == $userUuid) || ($userLevel !== "guest");

                if ($canEdit) {
                echo "<td><a href='docs/edit.php?id=" .  $row['id']  . "'><i class='fa-solid fa-id-card' style='color: #000;'></i></a></td>";
                } else {
                    echo "<td>";
                    echo " ";
                    echo "</td>";
                }
                if ($canEdit) {
                    echo "<td>";
                    $id = base64_encode($row['id']);

                    echo "<a href='editar.php?id=" . $id . "'><i class='fa-solid fa-pen-to-square'></i></a>";

                    if (!$isGuest) :
                        echo " | <a href='javascript:void(0);' onclick='confirmDelete(\"" . $id . "\")'><i class='fa-solid fa-trash' style='color: red;'></i></a> ";
                    endif;
                    echo "</td>";
                } else {

                    echo "<td>";
                    echo " ";
                    echo "</td>";
                }
                echo "<td>";
                echo "<input type='checkbox' id='active' name='active' " . ($row['active'] == 1 ? 'checked' : '') . " readonly onclick='return false;'>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15'>No team members found.</td></tr>";
        }
        // Close database connection
        $conn->close();
        ?>

    </table>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this member?")) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>
</body>

</html>