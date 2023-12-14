<?php

$hash = "";

if(isset($_POST['submit']) )
{
 
  $hash = $_POST['hash'];

}

$collect1 = substr($hash, 0, 5);
$collect2 = substr($hash,-4);
$es = "...";


//echo $coletar1;
//echo $es;
//echo $coletar2;

?>
<form method="post" action="hash.php"> 
Digite sua hash: <input type="text" name="hash" value="<?php echo $hash;?>">

Resultado:  <input type="text" value="<?php echo $collect1.$es.$collect2;?>"/><hr/>
<input type="submit" name="submit" value="Submit">  
</form>
