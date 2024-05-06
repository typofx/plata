<?php
// Incluir o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verificar se os IDs foram passados
if (isset($_GET['id_en']) && isset($_GET['id_es']) && isset($_GET['id_pt'])) {
    // Obter os IDs passados por GET
    $id_en = $_GET['id_en'];
    $id_es = $_GET['id_es'];
    $id_pt = $_GET['id_pt'];

    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obter os dados do formulário
        $task_date = $_POST['task_date'];
        $task_done = isset($_POST['task_done']) ? 1 : 0;
        $task_highlighted = isset($_POST['task_highlighted']) ? 1 : 0;
        $semester = $_POST['semester'];
        $task_goal_en = $_POST['task_goal_en'];
        $task_goal_es = $_POST['task_goal_es'];
        $task_goal_pt = $_POST['task_goal_pt'];

        // Atualizar os dados na tabela em inglês
        $sql_update_en = "UPDATE granna80_bdlinks.roadmap_en SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_en' WHERE task_id=$id_en";
        $conn->query($sql_update_en);

        // Atualizar os dados na tabela em espanhol
        $sql_update_es = "UPDATE granna80_bdlinks.roadmap_es SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_es' WHERE task_id=$id_es";
        $conn->query($sql_update_es);

        // Atualizar os dados na tabela em português
        $sql_update_pt = "UPDATE granna80_bdlinks.roadmap_pt SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_pt' WHERE task_id=$id_pt";
        $conn->query($sql_update_pt);

        // Redirecionar após a atualização
        echo "<script>window.location.href = 'index.php';</script>"; // R
        exit();
    }

    // Exemplo de consulta SQL para selecionar os dados correspondentes à ID em inglês
    $sql_select_en = "SELECT * FROM granna80_bdlinks.roadmap_en WHERE task_id = $id_en";
    $result_select_en = $conn->query($sql_select_en);
    $row_en = $result_select_en->fetch_assoc();

    // Exemplo de consulta SQL para selecionar os dados correspondentes à ID em espanhol
    $sql_select_es = "SELECT * FROM granna80_bdlinks.roadmap_es WHERE task_id = $id_es";
    $result_select_es = $conn->query($sql_select_es);
    $row_es = $result_select_es->fetch_assoc();

    // Exemplo de consulta SQL para selecionar os dados correspondentes à ID em português
    $sql_select_pt = "SELECT * FROM granna80_bdlinks.roadmap_pt WHERE task_id = $id_pt";
    $result_select_pt = $conn->query($sql_select_pt);
    $row_pt = $result_select_pt->fetch_assoc();

    // Aqui você pode exibir os formulários de edição e processar os dados atualizados conforme necessário
} else {
    // Caso os IDs não tenham sido passados corretamente, redirecionar ou exibir uma mensagem de erro
    echo "Error"; // Redirecionar para alguma página de erro ou de volta para a página anterior
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Tarefa</title>
    <!-- Adicione aqui seus estilos CSS se necessário -->
</head>
<body>

<h2>Editar Tarefa</h2>

<!-- Exemplo de formulário para edição dos dados -->
<form method="POST" action="">
    <!-- Campo de formulário para editar a data da tarefa -->
    <label>Task Date:</label>
    <input type="date" name="task_date" value="<?php echo $row_en['task_date']; ?>"><br>
    
    <!-- Campo de formulário para editar a conclusão da tarefa -->
    <label>Task Done:</label>
    <input type="checkbox" name="task_done" <?php echo ($row_en['task_done'] == 1) ? 'checked' : ''; ?>><br>
    
    <!-- Campo de formulário para editar o destaque da tarefa -->
    <label>Task Highlighted:</label>
    <input type="checkbox" name="task_highlighted" <?php echo ($row_en['task_highlighted'] == 1) ? 'checked' : ''; ?>><br>
    
    <!-- Campo de formulário para editar o semestre -->
    <label>Semester:</label>
    <input type="text" name="semester" value="<?php echo $row_en['semester']; ?>"><br>
    
    <!-- Campos de formulário para editar os dados da tarefa em inglês -->
    <label>Task Goal EN:</label>
    <input type="text" name="task_goal_en" value="<?php echo $row_en['task_goal']; ?>"><br>

    <!-- Campos de formulário para editar os dados da tarefa em espanhol -->
    <label>Task Goal ES:</label>
    <input type="text" name="task_goal_es" value="<?php echo $row_es['task_goal']; ?>"><br>

    <!-- Campos de formulário para editar os dados da tarefa em português -->
    <label>Task Goal PT:</label>
    <input type="text" name="task_goal_pt" value="<?php echo $row_pt['task_goal']; ?>"><br>

    <!-- Adicione mais campos de formulário conforme necessário -->

    <!-- Botão de envio do formulário -->
    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>
