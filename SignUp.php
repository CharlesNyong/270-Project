<?php
switch ($_GET["blnError"]) {
  case 1:
    echo '<script>alert("All fields must be filled!!")</script>';
  break;

  case 2:
    echo '<script>alert("Your confirmation password doesn\'t match!")</script>';
  break;  

  case 3:
    echo '<script>alert("There was a problem creating your account.\nPlease try again!!")</script>';
  break;
  
  default:
    # code...
  break;
}
// if(isset($_GET["blnError"])){

//   echo '<script>alert("There was a problem creating your account.\nPlease try again!!")</script>';
// }
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

    <title>Signup page</title>

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

  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="POST" action="registerUser.php">
        <h2 class="form-signin-heading">New User</h2>
        <label for="Username" class="sr-only">Email address</label>
        <input type="text" id="Username" class="form-control" value="Username" name="inputUserName" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputNewPassword" class="form-control" placeholder="Password" name="inputNewPassword">
        <label for="inputPassword" class="sr-only">Confirm Password</label>
        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm Password" name="ConfirmPassword">
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="signin">Create Account</button>
        <input type="hidden" value="1" name="signup_Page"></input>
      </form>
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
