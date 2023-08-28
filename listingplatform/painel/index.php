<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.112.5">
  <title>Login - Listin Places</title>








  
<style>
  /* CSS para modificar as cores */
  .form-control:focus {
    border-color: #ffb800;
    box-shadow: 0 0 0 0.25rem rgba(255, 184, 0, 0.25);
  }
  .form-check-input:checked {
    background-color: #ffb800;
    border-color: #ffb800;
  }
  .btn-primary {
    background-color: #ffb800;
    border-color: #ffb800;
  }
  .btn-primary:hover {
    background-color: black;
    border-color: black;
  }
</style>

  <!-- Custom styles for this template -->
  <link href="sign-in.css" rel="stylesheet">
</head>

<body>
 



  <main  style="text-align: center">
    <form  method="post" action="login_process.php">
      <img class="mb-4" src="assets/teste1.png" alt="" width="300px" height="300px">
      <h4 style="text-align: center; color:black"><b>FAZER LOGIN</b></h4>
      
     

      <div >
        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">E-mail</label>
      </div>
      <div>
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Type your password">
        <label for="floatingPassword">password</label>
      </div>

      <div >
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
         Salvar senha
        </label>
      </div>
      <button type="submit">Entrar</button>
      <p class="mt-5 mb-3 text-body-secondary">&copy; <?php echo date('Y'); ?></p>

    </form>
  </main>


</body>

</html>
