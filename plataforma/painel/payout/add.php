<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

// Consulta para buscar os dados da tabela team_docs
$sql_team = "SELECT uuid, member_name, private_email FROM granna80_bdlinks.team_docs";
$result_team = $conn->query($sql_team);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter o UUID selecionado no formulário
    $employee_uuid = $_POST['employee'];
    $rate = $_POST['rate'];
    $pay_type = $_POST['pay_type'];

    // Agora, buscar o nome do funcionário e o e-mail com base no UUID
    $sql_employee = "SELECT member_name, private_email FROM granna80_bdlinks.team_docs WHERE uuid = '$employee_uuid'";
    $result_employee = $conn->query($sql_employee);

    if ($result_employee->num_rows > 0) {
        $row_employee = $result_employee->fetch_assoc();
        $employee_name = $row_employee['member_name'];
        $employee_email = $row_employee['private_email'];

        // Inserir os dados no banco incluindo o UUID
        $sql = "INSERT INTO granna80_bdlinks.payout (uuid, employee, rate, pay_type, employee_email) 
                VALUES ('$employee_uuid', '$employee_name', '$rate', '$pay_type', '$employee_email')";

        if ($conn->query($sql) === TRUE) {
            echo "Novo registro criado com sucesso.";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Funcionário não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payout</title>
</head>
<body>
    <h1>Add New Payout</h1>
    <form action="add.php" method="post">
        <label for="employee">Employee:</label><br>
        <select id="employee" name="employee" required>
            <option value="">Selecione um funcionário</option>
            <?php
            if ($result_team->num_rows > 0) {
                // Preencher o select com os dados da tabela team_docs
                while ($row = $result_team->fetch_assoc()) {
                    echo "<option value='" . $row['uuid'] . "'>" . $row['member_name'] . " - " . $row['private_email'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum funcionário encontrado</option>";
            }
            ?>
        </select><br><br>

        <label for="rate">Rate:</label><br>
        <input type="text" id="rate" name="rate" required><br><br>

        <label for="pay_type">Pay Type:</label><br>
        <select id="pay_type" name="pay_type" required>
            <option value="Hourly">Hourly</option>
            <option value="Weekly">Weekly</option>
            <option value="Biweekly">Biweekly</option>
        </select><br><br>

        <input type="submit" value="Add Payout">
    </form>

    <br>
    <a href="index.php">Back to Payout List</a>
</body>
</html>
