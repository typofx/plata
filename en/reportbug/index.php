<?php
// define variables and set to empty values
$emailSubject = "";
$ComboBugLink = "";
$ComboBugOS = "";
$ComboBugBrowser = "";
$emailUserReporter = "";
$TextAreaComment = "";
$headers = "";
$emailMessage = "";

if(isset($_POST['submit']) )
 {
  $emailSubject = "Bug Report - " . "USER-NAME";
  $ComboBugLink = $_POST['ComboBugLink'];
  $ComboBugOS = $_POST['ComboBugOS'];
  $ComboBugBrowser = $_POST['ComboBugBrowser'];
  $emailUserReporter = $_POST['emailUserReporter'];
  $TextAreaComment = $_POST['TextAreaComment'];
  $headers = "From: noreply@plata.ie";
  
  $emailMessage = "Saida das strings:"."\n".
                  "User email: ".$emailUserReporter."\n"."\n".
                  "Where was this bug found? Link: ".$ComboBugLink."\n".
                  "Browser: ".$ComboBugBrowser."\n".
                  "Operating System: ".$ComboBugOS."\n".
                  "Comment: ".$TextAreaComment
                  ;
  
  mail($emailUserReporter, $emailSubject, $emailMessage, $headers);
  
  header("location: reportbugReturn.php");
 }

?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="button.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<title>Plata Token - Bug Report</title>

</head>
<body>
<div class="container">
<p class="login-text" style="font-size: 1.5rem; font-weight: 600;">Bug Report Form</p>
<br>

<form method="post" action="#" class="login-email">  

<div class="input-group">
<input type="email" placeholder="Email" id="emailUserReporter" name="emailUserReporter" value="<?php echo $emailUserReporter;?>" required>
</div>

<div class="input-group">
 <select id="ComboBugLink" name="ComboBugLink" class="dropdown" value="<?php echo $ComboBugLink;?>" required>
   <option>Where was this bug?</option>
   <option value="Link1">Main Page (index)</option> 
   <option value="Link2">Log in Page</option>
   <option value="Link3">Meet the Team Page</option>
 </select>
</div>

<div class="input-group">
<select id="ComboBugBrowser" name="ComboBugBrowser" class="dropdown" value="<?php echo $ComboBugBrowser;?>" required>
  <option >Which browser?</option>
  <option value="Brave">Brave</option>
  <option value="Chrome">Chrome</option>
  <option value="FireFox">FireFox</option>
  <option value="Opera">Opera</option>
  <option value="Tor">Tor</option>
</select>
</div>

<div class="input-group">
<select id="ComboBugOS" name="ComboBugOS" class="dropdown" value="<?php echo $ComboBugOS;?>" required>
  <option >Operating System</option>
  <option value="Android">Android</option>
  <option value="Linux">Linux</option>
  <option value="MacOS">MacOS</option>
  <option value="OSX">OSX</option>
  <option value="Windows">Windows</option>
</select>
</div>

<div class="input-group">
 <textarea id="TextAreaComment" name="TextAreaComment" value ="<?php echo $TextAreaComment;?>" rows="4" maxlength="81" required>Please, help our Dev Team typing more details about the issue.</textarea>
</div>
 
<div class="input-group">
    <br><br><p style="color:white;">_</p>
    <br>
    <button id="submitButton" name="submit" class="btn">Submit Report</button>

</div>
<div class="input-group">
<br>
</div>

</form>

</body>
</html>