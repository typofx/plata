<?php
$cookie_name = "apa";
$cookie_value = "John Doe";
?>
<html>
<body>

<?php
if(!isset($_COOKIE[$cookie_name])) {
  echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
  echo "Cookie '" . $cookie_name . "' is set!<br>";
  echo "Value is: " . $_COOKIE[$cookie_name];
}
?>

<!DOCTYPE html>
<html>
     
<head>
    <title></title>
</head>
 
<body>
<?php

        if(array_key_exists('button1', $_POST)) {
            button1();
            setcookie("apa", "John Doe", time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        function button1() {
            echo "This is Button1 that is selected";
            
        }

    ?>
 
    <form method="post">
        <input type="submit" name="button1" class="button" value="Button1" />
    </form>

</body>
</html>