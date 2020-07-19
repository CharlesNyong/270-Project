<?php
session_start();

if($_POST["logOut"]){
  //setcookie("blnLogOut", 1);
  session_destroy();
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
//var_dump($_COOKIE);
if($_SESSION["blnAuthenticated"] != 1){  
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}

if($_POST["comment"]){
	if(!sendComment($_POST["comment"])){
		echo "<script>alert('Oops! something went wrong.. try again');</script>";
	}
	else{
		echo "<script>alert('Comment sent! Thank you...');</script>";
	}
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Contact Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }
   
   	address{
   		font-style: italic;
   		font-weight: bold;
   	}
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
  <script>
  		function checkField(){
  			if(document.getElementById("comment").value == ""){
  				alert("Please fill out a comment first!");
  				return;
  			}
  			else{
  				document.getElementById("commentFrm").submit();
  			}
  		}

  		function logOut(){
        //alert("in log out");
        localStorage.clear();
        document.getElementById("logOut").value = 1;
        document.getElementById("logOutFrm").submit();
        //window.location.href = "http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php";
      }
  </script>
</head>
<body>
	<div class="jumbotron">
  <div class="container text-center">
    <h1>Contact Manager</h1>      
    <p>(Store, Search and Edit Your Contacts)</p>
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?echo"homePage.php?UserID=".$_COOKIE['intUserID'];?>">All Contacts</a></li>
        <li><a href="newContact.php">Create Contact</a></li>
        <li><a href="comment.php">Comment</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a onclick="logOut()"><span class="glyphicon glyphicon-user"></span> Log out</a></li>
      </ul>
    </div>
  </div>
</nav>
  <div class="container">
	  <h2>Comment</h2>
	  <p>Let me know what you think of this site</p>
	  <br/>
	  <form id="commentFrm" method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
	    <div class="form-group">
	      <label for="comment">Comment:</label>
	      <textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
	    </div>
	    <input type="button" onclick="checkField()" value="Send Comment" />
	  </form>
  </div>
  <form id="logOutFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
        <input type="hidden" value="" name="logOut"  id="logOut"/>
    </form> 
<br><br>
<footer class="container-fluid text-center">
<address>	
 <p><a href="comment.php">Send comment</a></p>  
 <p>You can contact me @ <b>2263486563</b><p>
 </address>	
</footer>
</body>
</html>
<?$strHTML .= ob_get_contents();
	ob_end_clean();
	echo $strHTML;

	function sendComment($strComment){
		if(mail("asikpo@uwindsor.ca", "60-270 project comment!", $strComment)){
			return true;			
		}
		else{
			return false;
		}
	
	}
?>
