
<form method="POST">
	<input name="submit" type="submit" value="Gerar numeros"/>
</form>

<?php

if(isset($_POST["submit"])){

$values = array();


$max_num = 5;
for ($x=0; $x<$max_num; $x++){
    $random_num = rand(0,9);
    
   array_push($values,$random_num); 
    echo ($random_num); 
   echo" ";
}
}else{
    header("Location: vetor.php");
}
?>