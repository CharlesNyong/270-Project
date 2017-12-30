<?php
include_once("connection.php");
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

if($_POST["strSaveForm"]){
	saveContactForUser($_COOKIE["intUserID"]);
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
  <link rel="stylesheet" href="css/newContact.css"/>
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
    	function saveContact(){
    		/*TODO: if time permits validate the phone number field before saving to DB*/
    		if(document.getElementById("strName").value =="" || document.getElementById("strEmail").value =="" ||
    			document.getElementById("strPhoneNo").value ==""){
    			alert("Required Fields:\nName\nEmail\nPhone!");
    			return;
    		}
    		else{
    			document.getElementById("strSaveForm").value = 1;
    			document.getElementById("newContactFrm").submit();
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
  		<center><h3>New Contact</h3><br/></center>
	<div class="contactFormDiv">	
		<form class="form-horizontal" id="newContactFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
		  <div class="form-group">
		    <label for="strName" class="col-sm-2 control-label">Name&nbsp;&nbsp;<span style="color:red; font-size:18px;">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="strName" name="strFullName" placeholder="First And Last Name">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="strEmail" class="col-sm-2 control-label">Email &nbsp;&nbsp;<span style="color:red; font-size:18px;">*</span></label>
		    <div class="col-sm-10">
		      <input type="email"  class="form-control" id="strEmail" name="strEmail" placeholder="Email">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="strPhoneNo" class="col-sm-2 control-label">Phone&nbsp;&nbsp;<span style="color:red; font-size:18px;">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="strPhoneNo" class="form-control" id="strPhoneNo" placeholder="Phone Number">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="strPhone2" class="col-sm-2 control-label">Phone 2</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="strPhone2" name="strPhone2" placeholder="Phone Number 2">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="strPhone3" class="col-sm-2 control-label">Phone 3</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="strPhone3" name="strPhone3" placeholder="Phone Number 3">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button class="btn btn-default" onclick="saveContact()">Save Contact</button>
		    </div>
		  </div>
		  <input type="hidden" id="strSaveForm" name="strSaveForm" value="0"/> 
		</form>
	</div>
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

	function saveContactForUser($intUserID){
		global $connection;
		$arrPhoneNumbers = array();
		$strFullName = $_POST["strFullName"];
		$strEmail = $_POST["strEmail"];
		$arrPhoneNumbers[] = $_POST["strPhoneNo"];
		if(!empty($_POST["strPhone2"])){
			$arrPhoneNumbers[] = $_POST["strPhone2"];
		}
		if(!empty($_POST["strPhone3"])){
			$arrPhoneNumbers[] = $_POST["strPhone3"];
		} 

		$strSQL = "INSERT INTO asikpo_270Project.tblContact
					(intUserID, strEmail, strContactName)
					VALUES ('$intUserID', '$strEmail', '$strFullName')";

		$rsResult = mysqli_query($connection, $strSQL);
		//echo "Query Used: ". $strSQL;
		$intContactID = mysqli_insert_id($connection);

		if($intContactID){ // if initial contact was successfully saved
			for ($i=0; $i < count($arrPhoneNumbers); $i++) {
				$strValue = $arrPhoneNumbers[$i]; 
				$strSQL = "INSERT INTO asikpo_270Project.tblPhoneNumber
							(intContactID, strPhoneNo)
							VALUES ('$intContactID', '$strValue')";	
				$rsResult = mysqli_query($connection, $strSQL);			
			}

			if($rsResult){
				echo '<script>alert("Contact successfully Created!")</script>';
				//echo '<script>window.location.href = "http://asikpo.myweb.cs.uwindsor.ca/60270/Project/homePage.php?UserID=$intUserID";</script>';
			}
		}
	}
?>	
