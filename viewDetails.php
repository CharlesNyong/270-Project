<?php
if($_COOKIE["blnAuthenticated"] != 1){
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
include("connection.php");
$arrAllContacts = array();

//var_dump($arrAllContacts);
// prevent others from viewing someone elses contact information
if($_COOKIE["intUserID"] != $_GET["UserID"] && $_GET["UserID"] != ""){ 
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/Error.php");
}
 // initial condition
if(isset($_GET["UserID"]) && isset($_GET["ContactID"])){
	$readOnlyAttr = readonly;
 // echo "Get condition";
	$arrAllContacts = getContact($_GET["UserID"], $_GET["ContactID"]);
	//var_dump($arrAllContacts);
}
//var_dump($_POST);
if($_POST["blnEdit"]){
	$readOnlyAttr = "";
	$arrAllContacts = getContact($_POST["intUserID"], $_POST["intContactID"]);
	
}
else if($_POST["blnSave"]){
	$readOnlyAttr = readonly;
	$arrAllContacts = updateContact($_POST["intUserID"], $_POST["intContactID"]);
	//var_dump($arrAllContacts);
}
// set readonly class based on 
$readonlyCSS = $readOnlyAttr ? inputReadonly : "";

//var_dump($arrAllContacts);
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

    <title>Details page</title>
    <link href="css/details.css" rel="stylesheet">
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
    	//localStorage.setItem("UserID", -1); // initial declaration
    	window.onload = function(){
    		<?if(isset($_GET["UserID"]) && isset($_GET["ContactID"])){
    			$UserID = $_GET['UserID'];
    			$ContactID = $_GET["ContactID"];
    		}?>
    		var intUserID = '<?echo $UserID?>';
    		var intContactID = '<?echo $ContactID?>';

    		// ensures that if users change the userID url they can't view the page
	        if(intUserID && localStorage.getItem("UserID") != intUserID && localStorage.getItem("UserID") != ""){
	            //alert("in Case");
	             window.location.href = "http://asikpo.myweb.cs.uwindsor.ca/60270/Project/Error.php";
	             return;
	        }

    		if(intUserID && intContactID){
    			localStorage.setItem("UserID", intUserID);
    			localStorage.setItem("ContactID", intContactID);
    		}	
    		//alert("UserID: " + localStorage.getItem("UserID"));
    	}

    	function editContact(){
    		if(localStorage.getItem("UserID") && localStorage.getItem("ContactID")){
    			document.getElementById("intUserID").value = localStorage.getItem("UserID");
    			document.getElementById("intContactID").value = localStorage.getItem("ContactID");
    		}
    		document.getElementById("blnEdit").value = 1;
    		document.getElementById("filterFrm").submit();
    	}

    	function updateContact(){
    		if(localStorage.getItem("UserID") && localStorage.getItem("ContactID")){
    			document.getElementById("intUserID").value = localStorage.getItem("UserID");
    			document.getElementById("intContactID").value = localStorage.getItem("ContactID");
    		}
    		document.getElementById("blnSave").value = 1;
    		document.getElementById("filterFrm").submit();	
    	}
    	// window.onbeforeunload = function(){
     //    //alert("unload fired");
     //    localStorage.clear();
     //  }
    </script>
    </head>
    <body>
    	<h2 style="position:absolute; top:-10px;">Contact Details</h2><br/>
    	<div id="imageDiv"><img src="images/imageDefault.jpg" width="150" height="150"></img></div>
  	<form id="filterFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
	 <table class = "table table-hover" border="0">
	   <thead>
	      <tr>
	         <th>Fields</th>
	         <th>Data</th>
	      </tr>
	   </thead>
	   <tbody>
	      <?if(!empty($arrAllContacts)){ 
		      foreach ($arrAllContacts as $intContactID => $arrContact) {?>
		            <?foreach ($arrContact as $mixKey => $mixData) {
		                $strLabel = "";
		                $strFieldType = "";
		                $strNameAttr = "";?>	
		                <?if($mixKey != "intUserID" && $mixKey != "intContactID"){
		                	if($mixKey == "strEmail"){
		                  		$strLabel = "Email";
		                  		$strFieldType = "email";
		                  	}
		                  	else if($mixKey == "strContactName"){
		                  		$strLabel = "Full Name";
		                  		$strFieldType = "text";	
		                  	}
		                  	else if($mixKey == "strPhoneNo"){
		                  		$strLabel = "Phone Number";
		                  		$strFieldType = "text";		
		                  	}
		                  	$strNameAttr = $mixKey;
		                  	?>
		                  <tr>
		                  <td>
		                  		<label> <?echo $strLabel;?></label>
		                  </td>
		                  <td title="value">
		                      <input type="<?echo $strFieldType;?>" name="<?echo $strNameAttr;?>" value="<?echo $mixData;?>" class="<?echo $readonlyCSS;?>" <?echo $readOnlyAttr?>/>
		                  </td>
		                  </tr>   
		                  <?}
		              }?>
		        <?}
		       }
		       else{?>
		       		<tr><td colspan="2"><h2>Contact does not exist! </h2></td></tr>	
		       <?}?>
	   </tbody>
	</table>
	<?if(!empty($arrAllContacts)){?>
	<span style="margin-left:3px;"><button onclick="editContact()"> Edit <img src="images/editIcon.jpg" width="25" height="30"/></button>
	&nbsp;&nbsp;<button onclick="updateContact()">Save <img src="images/saveCheckmark.png" width="25" height="30"/></button>
	</span>
	<?}?>  
		<input type="hidden" id="blnEdit" name="blnEdit" value="0"></input>
		<input type="hidden" id="blnSave" name="blnSave" value="0"></input>
     	<input type="hidden" id="intUserID" name="intUserID" value="0"></input>
      	<input type="hidden" id="intContactID" name="intContactID" value="0"></input>	
	</form>  
	</body>
    </html>	
<?
	$strHTML .= ob_get_contents();
	ob_end_clean();
	echo $strHTML;

	/* saves all the contacts to be displayed
		into an array used to display contacts on the page
	*/
	function getContact($intUserID, $intContactID){
		//echo "in getContact UserID = ".$intUserID. " ContactID = ". $intContactID;
		$arrContacts = array();
	    global $connection;
	    $strSQL = "SELECT strContactName, strPhoneNo, strEmail, tblContact.intUserID, tblContact.intContactID
	                FROM asikpo_270Project.tblContact
	                INNER JOIN asikpo_270Project.tblUsers
	                ON (tblUsers.intUserID)
	                INNER JOIN asikpo_270Project.tblPhoneNumber
	                ON (tblContact.intContactID = tblPhoneNumber.intContactID)
	                WHERE tblContact.intUserID = '$intUserID'
	                AND tblContact.intContactID = '$intContactID'";
	    $rsResult = mysqli_query($connection, $strSQL);
	      //echo "Query Used: ". $strSQL;
	    while ($arrRow = mysqli_fetch_assoc($rsResult)) {
	        //$this->intAttendanceID = $arrRow["intAttendanceID"];
	        $arrContacts[$arrRow["intContactID"]] = $arrRow;
	      }
	   // echo "Given ID: ". $intUserID;
	      return $arrContacts;
	}

	function updateContact($intUserID, $intContactID){
		$arrOldContactInfo = getContact($intUserID, $intContactID);
		global $connection;
		$strEmail = $_POST["strEmail"];
		$strContactName = $_POST["strContactName"];
		$strPhoneNo = $_POST["strPhoneNo"];
		$strSQL = "UPDATE asikpo_270Project.tblContact
				SET";
		foreach ($arrOldContactInfo as $intContactID => $arrInfo) {
			foreach ($arrInfo as $mixKey => $mixData){
				if($mixKey != "intUserID" && $mixKey != "intContactID"){
                	if($mixKey == "strEmail" && $mixData != $_POST["strEmail"]){ // email was changed
                  		$strSQL .= " strEmail = '$strEmail'".","; 
                  		$blnDataChanged = true;	
                  	}
                  
                  	if($mixKey == "strContactName" && $mixData != $_POST["strContactName"]){
                  		$strSQL .= " strContactName = '$strContactName'".",";
                  		$blnDataChanged = true;
                  	}
                  	
                  	if($mixKey == "strPhoneNo" && $mixData != $_POST["strPhoneNo"]){
                  		$blnPhoneNoChanged = true;
                  	}
				}
			}		
		}
		if($blnDataChanged){
			$strSQL = substr($strSQL, 0, strlen($strSQL)-1); // drop extra commas
			$strSQL .= " WHERE intContactID = '$intContactID' AND intUserID = '$intUserID' ";
			
			$rsResult = mysqli_query($connection, $strSQL);
		}

		if($blnPhoneNoChanged){
			$strSQL = "UPDATE asikpo_270Project.tblPhoneNumber 
						SET strPhoneNo = '$strPhoneNo' 
						WHERE intContactID = '$intContactID' ";
			//echo "Update query: ".$strSQL;			
			$rsResult = mysqli_query($connection, $strSQL);						
		}

		$arrUpdatedContact = getContact($intUserID, $intContactID);

		return $arrUpdatedContact;
	}

?>
