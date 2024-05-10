<?php session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>
</head>

<body>
    <h2>Team Members List</h2>
    <a href="add.php">Add new member</a>

    <table>
        <tr>
            <th>Profile Picture</th>
            <th>Name</th>
            <th>Position</th>
            <th>Social Media</th>
            <th>Actions</th>
        </tr>

        <?php

        // Include database configuration file
        include "conexao.php";

        // SQL query to select all team members
        $sql = "SELECT * FROM granna80_bdlinks.team";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display each team member in a table row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='" . $row['teamProfilePicture'] . "' width='100'></td>";
                echo "<td>" . $row['teamName'] . "</td>";
                echo "<td>" . $row['teamPosition'] . "</td>";
                echo "<td>";
                echo "Social Media 1: " . $row['teamSocialMedia0'] . "<br>";
                echo "Social Media 2: " . $row['teamSocialMedia1'] . "<br>";
                echo "Social Media 3: " . $row['teamSocialMedia2'];
                echo "</td>";
                echo "<td>";
                echo "<a href='editar.php?id=" . $row['id'] . "'>Edit</a>"; // Link to edit page with member ID
                echo " | ";
                echo "<a href='javascript:void(0);' onclick='confirmDelete(" . $row['id'] . ")'>Delete</a>"; // Link to delete with JavaScript confirmation
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No team members found.</td></tr>";
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