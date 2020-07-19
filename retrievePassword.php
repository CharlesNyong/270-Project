<?php
include_once("connection.php");
$arrUserInfo = array();
if($_POST["user-Name"]){
	if(!retrievePswrd($_POST["user-Name"])){
		echo "<script>alert('User name or email is incorrect!')</script>";
	}
}
else if($_POST["strEmail"]){
	if(!retrievePswrd($_POST["strEmail"])){
		echo "<script>alert('User name or email is incorrect!')</script>";
	}	
}
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Password</title>
	<script>
		window.onload = function(){
			var userName = "";
			var password = "";
			<?if($arrUserInfo["password"] && $arrUserInfo["userName"]){
				$userName = $arrUserInfo["userName"];
				$password = $arrUserInfo["password"];
				$blnFound = true;
			}
			//echo "user name: ". $userName;
			?>
			<?if($blnFound){?>
				document.getElementById("showPassword").innerHTML = " Password: "+ '<?echo $password?>';
				document.getElementById("showUsername").innerHTML = "Username: " + '<?echo $userName?>';  	
			<?}?>	
		}
	</script>
</head>
<body>
	<center><h3>Enter user name or email to retrieve password.<br/>
		<br/>If you've forgotten your username you can use your email to retrieve it.</h3></center><br/>
	<form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" style="margin-left: 30px;">
		<label>UserName:</label> <input type="text" id="strUserName" name="user-Name" /> <input type="submit" value="Go" />
			<p>OR</p>
		<label>Email:</label> <input type="email" id="strEmail" name="strEmail" />	<input type="submit" value="Go" />
	</form>
	<br/><br/>
	<p style="font-weight:bold;margin-left: 30px;" id="showPassword"></p>
	<p style="font-weight:bold;margin-left: 30px;" id="showUsername"></p><br/>
	<div style="margin-left: 30px;">
		<a href="loginPage.php">
			<button style="padding:10px;">
	     	<b>Login Page</b>
	     	</button>
		</a>
	</div>
</body>
</html>
<?


function retrievePswrd($userNameOrEmail){
	//echo "Input value ".$userNameOrEmail;
	global $connection;
	global $arrUserInfo;
	$strSQL = "SELECT intUserID, strPassword, strUserName
	FROM asikpo_270Project.tblUsers
	WHERE strUserName = '$userNameOrEmail' OR strEmailAddress = '$userNameOrEmail' ";

	$rsResult = mysqli_query($connection, $strSQL);
		//echo "Query Used: ". $strSQL;
	$arrRow = mysqli_fetch_assoc($rsResult);

	if($arrRow["intUserID"]){
		$arrUserInfo["password"] = $arrRow["strPassword"];
		$arrUserInfo["userName"] = $arrRow["strUserName"];
		return true;
	}
	else{
		return false;
	}
}
?>
