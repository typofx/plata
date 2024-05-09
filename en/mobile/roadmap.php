<style>

/*table, th, td {
    border: 1px solid black;
    color: black;
    }*/

.img-line {
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 47px;
    }

.roadmap-main-text {
    border-collapse: collapse;
    width: 100%;
    height: 80px; /* width="70%" height="80px"*/
    margin-left: auto;
    margin-right: auto;
    }
    
.roadmap-main-text .roadmap-header{
    background-color: #4C00B0;
    color: white;
    }
    
.roadmap-main-text .roadmap-icon{
    background-color: #8A00C2;
    width: 80px;
    }
    
.roadmap-table {
    border-collapse: collapse;
    width: calc(100% - 60px);
    margin-left: 30px;
    margin-top: 30px;
    margin-bottom: 0px;
    text-align: left;
    }
    
.roadmap-table h3 {
    text-align: left;

}

.roadmap-table .tdleft {
    text-align: center;
    width: 50px;
}

.roadmap-table .tdline1 {
    background-color: #9966CC;
    color: white;
}

.roadmap-table .tdline2 {
    background-color: #8A00C2;
    color: white;
}

.div-roadmap{
    background-color: #1C0024;
    padding-bottom: 30px;
}

.highlighted-text {
        color: yellow;
        /* You can add more CSS styles as needed */
    } 
</style>
<style>
    /* Estilo para ocultar o texto */
    .texto-oculto {
      overflow: hidden;
      position: relative;
    }

    /* Estilo para o botão */
    .botao-mostrar {
      cursor: pointer;
      color: blue;
    }

    .botao-mostrar {
    text-align: center; 
    color: white; 
    cursor: pointer; 
   
    padding: 10px 20px; 
    border-radius: 5px; 
  }

  .no-wrap {
    white-space: nowrap;
  }

  </style>

<section id="AnchorRoadmapMobile">
<div class="div-roadmap">
    
<table class="roadmap-main-text">
 	<tr>
		<td class="roadmap-header"><h2><?php echo  $txtRoadmap ?></h2></td>
		<td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg" alt=""></td>
	</tr>
    <td>
</table>

<?php


include 'conexao.php';

$language = substr($_SERVER['REQUEST_URI'], 1, 2);

//granna80_bdlinks.roadmap_en

$tableSuffix = ($language == "pt") ? "_pt" : (($language == "es") ? "_es" : "_en");

// Construct the SQL query
$sqlEN = "SELECT *
        FROM granna80_bdlinks.roadmap" . $tableSuffix . "
        WHERE YEAR(task_date) = 2023
        ORDER BY task_done DESC, task_id DESC";

$resultEN = mysqli_query($conn, $sqlEN);

if ($resultEN) {
    // Start the HTML table
    echo '<table class="roadmap-table">';
    
    // Arrays for tasks in Q1 and Q2
    $q1_tasks = array();
    $q2_tasks = array();

    while ($rowEN = mysqli_fetch_assoc($resultEN)) {
        $task_goalEN = $rowEN["task_goal"];
        
        // Check if task_done is equal to 1
        if ($rowEN["task_done"] == 1) {
          $task_goalEN = '✓ ' . $task_goalEN;
      } else {
          $task_goalEN = ' &ndash; ' . $task_goalEN;
      }
      

        // Check if task_highlighted is equal to 1 and add a CSS class
        $task_highlighted = $rowEN["task_highlighted"];
        if ($task_highlighted == 1) {
            $task_goalEN = '<span class="highlighted-text texto-oculto no-wrap" id="meuTexto" data-texto-completo="' . $task_goalEN . '"><l>' . $task_goalEN . '</l></span>';
        } else {
            $task_goalEN = '<span class="texto-oculto no-wrap" id="meuTexto" data-texto-completo="' . $task_goalEN . '"><l>' . $task_goalEN . ' </l></span>';
        }

        // Check if semester is equal to 1
        $semester = $rowEN["semester"];
        if ($semester == 1) {
            // Add to Q2
            $q2_tasks[] = $task_goalEN;
        } else {
            // Add to Q1
            $q1_tasks[] = $task_goalEN;
        }
    }

    // Populate the Q1 section
    echo '<tr>';
    echo '<td class="tdleft tdline1"><div class="centre cursor"><h2>S1</h2></div></td>';
    echo '<td class="tdline1 fontStyle"><br>';
    foreach ($q1_tasks as $q1_task) {
        echo $q1_task . '<br>';
    }
    echo '<br>';
    echo '</td>';
    echo '</tr>';

    // Populate the Q2 section
    echo '<tr class="noooo">';
    echo '<td class="tdleft tdline2 cursor"><div class="centre"><h2>S2</h2></div></td>';
    echo '<td class="tdline2 fontStyle"><br>';
    foreach ($q2_tasks as $q2_task) {
        echo $q2_task . '<br>';
    }
    echo '<br>';
    echo '</td>';
    echo '</tr>';

    // Close the HTML table
    echo '</table>';
} else {
    echo "Erro na consulta ao banco de dados.";
}
?>
 <!-- Botão para mostrar/ocultar o texto -->
 <div class="botao-mostrar" onclick="mostrarOcultarTexto()">Show more</div>

 <script>
  document.addEventListener('DOMContentLoaded', function() {
    mostrarOcultarTexto(true); // Chame a função ao carregar a página
  });

  function mostrarOcultarTexto(carregamento) {
    var spans = document.querySelectorAll('.texto-oculto');
    var botaoMostrar = document.querySelector('.botao-mostrar');

    spans.forEach(function(span) {
      var paragrafo = span.querySelector('l');
      var palavrasExibidas = 5.5; // Número desejado de palavras
      var palavras = paragrafo.textContent.split(/\s+/).filter(word => word !== '-' && word.toLowerCase() !== 'de'&& word.toLowerCase() !== 'la');

     


      

      if (carregamento) {
       
        var textoResumido = palavras.slice(0, palavrasExibidas).join(' ');
        paragrafo.innerHTML = textoResumido;
        botaoMostrar.innerHTML = 'Show more';
        if (!span.classList.contains('no-wrap')) {
          span.classList.add('no-wrap'); 
        }
      } else {
     
        if (palavras.length > palavrasExibidas) {
          
          var textoResumido = palavras.slice(0, palavrasExibidas).join(' ');
          paragrafo.innerHTML = textoResumido;
          botaoMostrar.innerHTML = 'Show more';
          span.classList.add('no-wrap'); 
        } else {
        
          paragrafo.innerHTML = span.getAttribute('data-texto-completo');
          botaoMostrar.innerHTML = 'Show Less';
          span.classList.remove('no-wrap'); 
        }
      }
    });
  }


  document.querySelectorAll('.texto-oculto').forEach(function(span) {
    span.setAttribute('data-texto-completo', span.textContent);
  });
</script>



</div>
</section>