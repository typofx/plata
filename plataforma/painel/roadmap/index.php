<?php
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

// Incluir o arquivo de conexão com o banco de dados
include 'conexao.php';

// Checando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Query para selecionar os dados da tabela em inglês
$sql_en = "SELECT * FROM granna80_bdlinks.roadmap_en";
$result_en = $conn->query($sql_en);

// Query para selecionar os dados da tabela em espanhol
$sql_es = "SELECT * FROM granna80_bdlinks.roadmap_es";
$result_es = $conn->query($sql_es);

// Query para selecionar os dados da tabela em português
$sql_pt = "SELECT * FROM granna80_bdlinks.roadmap_pt";
$result_pt = $conn->query($sql_pt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Roadmap </title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
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

<h2>Roadmap </h2>

<table>
  <tr>
    <th>ID</th>
    <th>Date</th>
    <th>Done</th>
    <th>Goal ENGLISH</th>
    <th>Goal ESPANISH</th>
    <th>Goal PORTUGUESE</th>
   
    <th>Semester</th>
    <th>Edit</th> <!-- Nova coluna para os botões de edição -->
  </tr>
  <?php
  // Inicializa os contadores de índice
  $index = 1;

  // Loop até que haja resultados em pelo menos uma das tabelas
  while (true) {
      $row_en = $result_en->fetch_assoc();
      $row_es = $result_es->fetch_assoc();
      $row_pt = $result_pt->fetch_assoc();

      // Se não houver mais resultados em nenhuma das tabelas, termina o loop
      if (!$row_en && !$row_es && !$row_pt) {
          break;
      }

      echo "<tr>";
      echo "<td>".$index."
      </td>";

      // Verifica e exibe a data da tarefa em inglês
      echo "<td>";
      if ($row_en) {
          echo date('d/m/Y', strtotime($row_en["task_date"]));
      }
      echo "</td>";

         // Verifica e exibe se a tarefa foi concluída
         echo "<td>";
         if ($row_en && $row_en["task_done"] == 1) {
             echo "&#x2713;";
         } else if ($row_en) {
             echo "No";
         }
         echo "</td>";

      // Verifica e exibe o objetivo da tarefa em inglês
      echo "<td>";
      if ($row_en && $row_en["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_en["task_goal"]."</span>";
      } else if ($row_en) {
          echo $row_en["task_goal"];
      }
      echo "</td>";

      // Verifica e exibe o objetivo da tarefa em espanhol
      echo "<td>";
      if ($row_es && $row_es["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_es["task_goal"]."</span>";
      } else if ($row_es) {
          echo $row_es["task_goal"];
      }
      echo "</td>";

      // Verifica e exibe o objetivo da tarefa em português
      echo "<td>";
      if ($row_pt && $row_pt["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_pt["task_goal"]."</span>";
      } else if ($row_pt) {
          echo $row_pt["task_goal"];
      }
      echo "</td>";
      
   
      
      // Exibe o semestre, pegando de qualquer uma das tabelas
      echo "<td>";
      if ($row_en) {
          echo $row_en["semester"];
      } else if ($row_es) {
          echo $row_es["semester"];
      } else if ($row_pt) {
          echo $row_pt["semester"];
      }
      echo "</td>";

      // Adiciona um botão de edição para cada linha
      echo "<td><a href='editar.php?id_en=" . ($row_en ? $row_en["task_id"] : "") . "&id_es=" . ($row_es ? $row_es["task_id"] : "") . "&id_pt=" . ($row_pt ? $row_pt["task_id"] : "") . "'>Edit</a></td>";

      echo "</tr>";

      // Incrementa o contador de índice
      $index++;
  }
  ?>
</table>

</body>
</html>
