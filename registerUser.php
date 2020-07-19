<?php
include("connection.php");
global $connection;
session_start();
	$strUsername = $_POST["inputUserName"];
	$strPassword = $_POST["inputNewPassword"];
	$strEmailAddr = $_POST["emailAddress"];
	$strConfirmPwd = $_POST["ConfirmPassword"];//var_dump($_POST);
	if($_POST){
		if(empty($strConfirmPwd) || empty($strPassword) || empty($strUsername) || empty($strEmailAddr)){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=1");
		}
		else if($strConfirmPwd != $strPassword){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=2");
		}
		else if(userAlreadyExist($strUsername)){
			header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=3");	
		}
		else{
			$strSQL = "INSERT INTO asikpo_270Project.tblUsers
						(strUserName, strPassword, strEmailAddress)
						VALUES ('$strUsername', '$strPassword', '$strEmailAddr')";

			$rsResult = mysqli_query($connection, $strSQL);
			//echo "Query Used: ". $strSQL;
			$intUserID = mysqli_insert_id($connection);
			if(!$rsResult){
				header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/signup.php?blnError=4");
			}
			else{
				setcookie("intUserID", $intUserID);
				$_SESSION["blnAuthenticated"] = 1;
				header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/homePage.php?UserID=".$intUserID);
			}				
		}
	}


	function userAlreadyExist($strUsername){
		global $connection;
		$strSQL = "SELECT intUserID  FROM asikpo_270Project.tblUsers
					WHERE strUserName = '$strUsername' ";
		$rsResult = mysqli_query($connection, $strSQL);
		$arrRow = mysqli_fetch_assoc($rsResult);

		if($arrRow["intUserID"]){
			return true;
		}
		else{
			return false;
		}
	}
?>
