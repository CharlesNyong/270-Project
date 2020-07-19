<?php
include("connection.php");
global $connection;
session_start();
	$username = $_POST["inputUserName"];
	$password = $_POST["inputPassword"];
	//var_dump($_POST);
	if($_POST){
		$strSQL = "SELECT * FROM asikpo_270Project.tblUsers
					WHERE strUserName = '$username'  AND strPassword = '$password'";

		$rsResult = mysqli_query($connection, $strSQL);
		//echo "Query Used: ". $strSQL;
		$arrRow = mysqli_fetch_assoc($rsResult);

		if(!$arrRow["intUserID"]){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php?blnError=1");
		}
		else{
			setcookie("intUserID", $arrRow["intUserID"]);
			//setcookie("blnAuthenticated", 1);
			$_SESSION["blnAuthenticated"] = 1; 
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/homePage.php?UserID=".$arrRow["intUserID"]);
		}				
		
	}
?>
