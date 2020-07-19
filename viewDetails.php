<?php
include("connection.php");
session_start();
$arrAllContacts = array();

if($_POST["logOut"]){
  //setcookie("blnLogOut", 1);
  session_destroy();
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
//var_dump($_COOKIE);
if($_SESSION["blnAuthenticated"] != 1){  
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
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
	setcookie("intUserID", $_GET["UserID"]);
}
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

//var_dump($_COOKIE);
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Details page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/details.css" />
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

    .inputReadonly{
		background-color: #FFFFCC;
	}

	.imageDiv{
		float: left;
	    border-radius: 6px;
	    margin-left: 5px;
	    margin-bottom: 10px;
	    border: 1px solid black;
	}

	.theader-color{
		background-color: #E5E4E2;
		font-size: 1.2em;
	}
  </style>
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

    	function redirect(){
    		window.location.href ="homePage.php?UserID="+localStorage.getItem("UserID");
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
		    <li class="active"><a onclick="redirect()">All Contacts</a></li>
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
    	<div class="imageDiv"><img src="images/imageDefault.jpg" width="150" height="150"></img></div>
	  	<form id="filterFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
		 <table class = "table table-hover" border="0">
		   <thead>
		      <tr>
		         <th class="theader-color">Fields</th>
		         <th class="theader-color">Data</th>
		      </tr>
		   </thead>
		   <tbody>
		       <?if(!empty($arrAllContacts)){
		       		$intPhoneNoCount =0;
			       foreach ($arrAllContacts as $intPhoneID => $arrContact) {
			       	$intPhoneNoCount ++;
			       	?>	
			             <?foreach ($arrContact as $mixKey => $mixData) {
			                $strLabel = "";
			                $strFieldType = "";
			                 $strNameAttr = "";?>	
			                 <?if($mixKey != "intUserID" && $mixKey != "intContactID" && $mixKey!= "intPhoneID"){
			                	if($mixKey == "strEmail"){
			                  		$strEmailValue = $mixData;
			                  		continue; // keep email for last
			                  	}
			                  	else if($mixKey == "strContactName"){
			                  		$strLabel = "Full Name";
			                  		$strFieldType = "text";
			                  		$strNameAttr = $mixKey;	
			                  	}
			                  	else if($mixKey == "strPhoneNo"){
			                  		$strLabel = "Phone Number ".$intPhoneNoCount;
			                  		$strFieldType = "text";		
			                  		$strNameAttr = $intPhoneID;
			                  	}
			                  	
			                  	?>
			                  <tr>
			                  <td>
			                  		<p style="font-weight:light; font-size:1.1em;"> <?echo $strLabel;?></p>
			                  </td>
			                  <td title="value">
			                      <input type="<?echo $strFieldType;?>" name="<?echo $strNameAttr;?>" value="<?echo $mixData;?>" class="<?echo $readonlyCSS;?>" <?echo $readOnlyAttr?>/>
			                  </td>
			                  </tr>   
			                  <?}
			              }?>
			        <?}?>
			        <tr>
	                  <td>
	                  		<p style="font-weight:light; font-size:1.1em;">Email</p>
	                  </td>
	                  <td title="value">
	                      <input type="email" name="strEmail" value="<?echo $strEmailValue;?>" class="<?echo $readonlyCSS;?>" <?echo $readOnlyAttr?>/>
	                  </td>
	                </tr>   
			       	<?}
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
	</div>
	<form id="logOutFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
        <input type="hidden" value="" name="logOut"  id="logOut"/>
       </form> 
	<br><br>
	<center>
	<footer class="container-fluid text-center">
	  <address>	
		 <p><a href="comment.php">Send comment</a></p>  
		 <p>You can contact me @ <b>2263486563</b><p>
	  </address>	
	</footer>
	</center> 
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
	    $arrUniqueContacts = array();
	    $strSQL = "SELECT DISTINCT strContactName, strPhoneNo, strEmail, tblContact.intUserID, tblContact.intContactID, intPhoneID
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
	        //$arrPhoneNumbers["intPhoneID"] = 
	        $arrContacts[$arrRow["intPhoneID"]] = $arrRow;
	    }
	    $blnFirstTime = 1;
	    foreach ($arrContacts as $intPhoneID => $arrContactInfo) {
    		if($blnFirstTime){
    			$initPhoneID = $intPhoneID;
    			$blnFirstTime = 0;
    		}
    		foreach ($arrContactInfo as $mixKey => $mixValue) {
    			if($initPhoneID != $intPhoneID){ // new row (not the first row) phoneID has changed	
    				if($mixKey == "strPhoneNo"){ // only change the phoneNumber cell for subsequent rows
    					$arrUniqueContacts[$intPhoneID][$mixKey] = $mixValue;
    				}
    			}
    			else{ // store information once unless its the phone Number data
    				$arrUniqueContacts[$intPhoneID][$mixKey] = $mixValue; 
    			}
    		}
	    }
	    return $arrUniqueContacts;
	   // echo "Given ID: ". $intUserID;
	      //return $arrContacts;
	}

	function updateContact($intUserID, $intContactID){
		$arrOldContactInfo = getContact($intUserID, $intContactID);
		global $connection;
		$arrChangedPhoneNumbers = array();
		$strEmail = $_POST["strEmail"];
		$strContactName = $_POST["strContactName"];
		
		$strSQL = "UPDATE asikpo_270Project.tblContact
				SET";
		foreach ($arrOldContactInfo as $intPhoneID => $arrInfo) {
			foreach ($arrInfo as $mixKey => $mixData){
				if($mixKey != "intUserID" && $mixKey != "intContactID" && $mixKey!= "intPhoneID"){
                	if($mixKey == "strEmail" && $mixData != $_POST["strEmail"]){ // email was changed
                  		$strSQL .= " strEmail = '$strEmail'".","; 
                  		$blnDataChanged = true;	
                  	}
                  
                  	if($mixKey == "strContactName" && $mixData != $_POST["strContactName"]){
                  		$strSQL .= " strContactName = '$strContactName'".",";
                  		$blnDataChanged = true;
                  	}
                  	
                  	if($mixKey == "strPhoneNo" && $arrOldContactInfo[$intPhoneID]["strPhoneNo"] != $_POST[$intPhoneID]){
                  		$arrChangedPhoneNumbers[$intPhoneID] = $_POST[$intPhoneID];
                  	}
				}
			}		
		}
		if($blnDataChanged){
			$strSQL = substr($strSQL, 0, strlen($strSQL)-1); // drop extra commas
			$strSQL .= " WHERE intContactID = '$intContactID' AND intUserID = '$intUserID' ";
			
			$rsResult = mysqli_query($connection, $strSQL);
		}

		if(!empty($arrChangedPhoneNumbers)){
			foreach ($arrChangedPhoneNumbers as $intPhoneID => $strPhoneNo) {
				$strSQL = "UPDATE asikpo_270Project.tblPhoneNumber 
							SET strPhoneNo = '$strPhoneNo' 
							WHERE intContactID = '$intContactID' 
							AND intPhoneID = '$intPhoneID' ";
				//echo "Update query: ".$strSQL;			
				$rsResult = mysqli_query($connection, $strSQL);
			}							
		}

		$arrUpdatedContact = getContact($intUserID, $intContactID);

		return $arrUpdatedContact;
	}

?>
