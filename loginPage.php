<?php
if($_GET["blnError"]){
  echo '<script>alert("Invalid username or password\nAccount may not exist!")</script>';
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signin page</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
      function signup(){
        //alert("signup called!");
         window.location.href = "http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php"; 
      }

      // function authenticate(){
      //   strUsername = document.getElementById("Username").value;
      //   strPassword = document.getElementById("inputPassword").value;

      //   sessionStorage.setItem("UserName", strUsername);
      //   sessionStorage.setItem("Password", strPassword);

      //   alert("UserName: " + sessionStorage.getItem("UserName"));   
    </script>

  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="POST" action="authenticate.php">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="Username" class="sr-only">Email address</label>
        <input type="text" id="Username" class="form-control" value="Username" name="inputUserName" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="inputPassword">
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="signin">Sign in</button>
      </form>
      <div class="form-signin"><button class="btn btn-lg btn-primary btn-block" onclick="signup();">Sign up</button></div>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?
$strHTML .= ob_get_contents();
ob_end_clean();
echo $strHTML;
 ?>
