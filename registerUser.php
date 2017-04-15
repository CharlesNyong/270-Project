<?php
include("connection.php");
global $connection;

	$strUsername = $_POST["inputUserName"];
	$strPassword = $_POST["inputNewPassword"];
	$strConfirmPwd = $_POST["ConfirmPassword"];//var_dump($_POST);
	if($_POST){
		if(empty($strConfirmPwd) || empty($strPassword) || empty($strUsername)){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=1");
		}
		else if($strConfirmPwd != $strPassword){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=2");
		}
		else{
			$strSQL = "INSERT INTO asikpo_270Project.tblUsers
						(strUserName, strPassword)
						VALUES ('$strUsername', '$strPassword')";

			$rsResult = mysqli_query($connection, $strSQL);
			//echo "Query Used: ". $strSQL;
			$intUserID = mysqli_insert_id($connection);
			if(!$rsResult){
				header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=3");
			}
			else{
				setcookie("intUserID", $intUserID);
				setcookie("blnAuthenticated", 1);
				header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/homePage.php?UserID=".$intUserID);
			}				
		}
	}
?>
