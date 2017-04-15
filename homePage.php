<?php
if($_COOKIE["blnAuthenticated"] != 1){
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
include("connection.php");
$arrAllContacts = array();

/*TODO: if time permits modify the code from using 
  GET to cookie. this way u don't have to worry about security checks
*/
// prevent others from viewing someone elses contact information
if($_COOKIE["intUserID"] != $_GET["UserID"] && $_GET["UserID"] != ""){ 
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/Error.php");
}

if(isset($_GET["UserID"])){
 // echo "Get condition";
	$arrAllContacts = displayAllContactsForUser($_GET["UserID"]);
}
else if($_POST["deleteContact"] == 1){
  deleteContact($_POST["intContactID"]);
  $arrAllContacts = displayAllContactsForUser($_POST["userID"]); // get updated contact list
  //echo "In delete case";
}
else if(isset($_POST["userID"]) && $_POST["blnGetContact"] != 1){
	$arrAllContacts = displayAllContactsForUser($_POST["userID"]);
}
if($_POST["blnGetContact"]){
	$arrAllContacts = getContact($_POST["nameSearch"]);
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

    <title>Home page</title>

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
    		
        <?if(isset($_GET["UserID"])){
    			$UserID = $_GET['UserID'];
    		}?>
          //alert("Initial local storage value: " + localStorage.getItem("UserID"));
          var intUserID = '<?echo $UserID?>';
        //var value = localStorage.getItem("UserID");

        if(intUserID){      
    			localStorage.setItem("UserID", intUserID);
    		}

    		//alert("UserID: " + localStorage.getItem("UserID"));
    	}

      // window.onbeforeunload = function(){
      //   //alert("unload fired");
      //   localStorage.clear();
      // }

    	function getContact(){
    		if(document.getElementById("nameSearch").value == ""){
    			alert("Name field must be entered!!");
    			return;
    		}

        if(document.getElementById("userID").value == ""){
          document.getElementById("userID").value = localStorage.getItem("UserID"); 
        }
    		//document.getElementById("UserID").value = localStorage.getItem("UserID");
    		//alert("Value of userID inputField: " + localStorage.getItem("UserID"));
         //alert("Value of userID inputField: " + document.getElementById("userID").value);	
    		document.getElementById("blnGetContact").value = 1;
    		//location.reload();
    		document.getElementById("filterFrm").submit();
    	}

      function redirect(strURL){
        //alert("URL: " + strURL);
        //return;
        window.location.href = strURL;
      }

      function callFunc(event){
        if(event.keyCode === 13){ // if enter key is pressed
          getContact();
          //alert("Enter Pressed");
        }
      }

      function deleteContact(intContactID){
        //alert("ID: " + intContactID);
        if(document.getElementById("userID").value == ""){
          document.getElementById("userID").value = localStorage.getItem("UserID"); 
        }
        document.getElementById("intContactID").value = intContactID;
        document.getElementById("deleteContact").value = 1;
        document.getElementById("filterFrm").submit();
      }

      function displayAllContacts(){
        if(document.getElementById("userID").value == ""){
          document.getElementById("userID").value = localStorage.getItem("UserID"); 
        }
        document.getElementById("blnGetContact").value = 0;
        document.getElementById("filterFrm").submit();
      }

    </script>
  </head>
  <body>
    
  	<h2 style="position:absolute; top:-10px;">Contact Manager</h2> <h4><a href="newContact.php">New contact</a></h4><br/>
  	<form id="filterFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
	  	<fieldset>
	  		<legend>Filter</legend>
	  		<label for="nameSearch">Name:</label>
	  		<input type="text" id="nameSearch" name="nameSearch" onkeypress="callFunc(event)"></input> <input type="button" value="Search" onclick="getContact()"/>
        <input type="button" value="All Contacts" onclick="displayAllContacts()"/>
	  	</fieldset>
	  	<?if(isset($_GET["UserID"])){?>
	  		<input type="hidden" id="userID" name="userID" value="<?echo $_GET['UserID'];?>"></input>
	  	<?}else{
	  		//echo "second case;"?>
			<input type="hidden" id="userID" name="userID" value=""></input>
	  	<?}?>
	  	<input type="hidden" id="blnGetContact" name="blnGetContact" value="0"></input>
      <input type="hidden" id="deleteContact" name="deleteContact" value="0"></input>
      <input type="hidden" id="intContactID" name="intContactID" value="0"></input>	
	</form>
	 <table class = "table table-hover" border="0">
    <?$strCaption = $_POST["blnGetContact"] == 1 ? "Contact Information for ".$_POST["nameSearch"] : "All Contacts";?>
   <caption><h4><?echo $strCaption;?></h4></caption>
   <thead>
      <tr>
         <th>Name</th>
         <th>Phone Number</th>
         <th>Email</th>
      </tr>
   </thead>
   <tbody>
      <?if(!empty($arrAllContacts)){ 
          foreach ($arrAllContacts as $intContactID => $arrContact) {?>
                <tr>
                <?foreach ($arrContact as $mixKey => $mixData) {
                    $strLink = "viewDetails.php?UserID=".$arrContact["intUserID"]."&ContactID=".$arrContact["intContactID"];?>
                    <?if($mixKey != "intUserID" && $mixKey != "intContactID"){?>
                      <td title="Click to go to the details page" onclick="redirect('<?echo $strLink?>')">
                          <?echo $mixData;?>
                      </td>   
                      <?}
                  }?>
                  <td><button  onclick="deleteContact('<?echo $arrContact["intContactID"]?>')">
                    Delete <img src="images/deleteIcon.png" width="25" height="30"/>
                  </button></td>
                </tr>
            <?}
          }else{?>
            <tr><td colspan="3"><h2>Oops! you might want to create some contacts!</h2></td></tr>
          <?}?>
   </tbody>
   
</table>
   
  </body>
  </html>
  <?
  	$strHTML .= ob_get_contents();
	ob_end_clean();
	echo $strHTML;

	/* saves all the contacts to be displayed
		into an array used to display contacts on the page
	*/
	function displayAllContactsForUser($intUserID){
    //echo "UserID in displayAllContactsForUser: ". $intUserID;
		$arrContacts = array();
    global $connection;
    $strSQL = "SELECT strContactName, strPhoneNo, strEmail, tblContact.intUserID, tblContact.intContactID
                FROM asikpo_270Project.tblContact
                INNER JOIN asikpo_270Project.tblUsers
                ON (tblUsers.intUserID)
                INNER JOIN asikpo_270Project.tblPhoneNumber
                ON (tblContact.intContactID = tblPhoneNumber.intContactID)
                WHERE tblContact.intUserID = '$intUserID'";
    $rsResult = mysqli_query($connection, $strSQL);
      //echo "Query Used: ". $strSQL;
    while ($arrRow = mysqli_fetch_assoc($rsResult)) {
        //$this->intAttendanceID = $arrRow["intAttendanceID"];
        $arrContacts[$arrRow["intContactID"]] = $arrRow;
      }
   // echo "Given ID: ". $intUserID;
      return $arrContacts;
	}

	function getContact($strFirstName){
	  $arrContacts = array();
    $intUserID = $_POST["userID"];
    global $connection;
    $strSQL = "SELECT strContactName, strPhoneNo, strEmail, tblUsers.intUserID, tblContact.intContactID
                FROM asikpo_270Project.tblContact
                LEFT JOIN asikpo_270Project.tblUsers
                ON (tblUsers.intUserID)
                LEFT JOIN asikpo_270Project.tblPhoneNumber
                ON (tblContact.intContactID)
                WHERE tblContact.intUserID = '$intUserID'
                AND strContactName LIKE '%$strFirstName%' "; //find values that contains "FirstName" in it
    $rsResult = mysqli_query($connection, $strSQL);
      //echo "Query Used: ". $strSQL;
    while ($arrRow = mysqli_fetch_assoc($rsResult)) {
        //$this->intAttendanceID = $arrRow["intAttendanceID"];
        $arrContacts[$arrRow["intContactID"]] = $arrRow;
      }
   // echo "Given ID: ". $intUserID;
      return $arrContacts;	
	}

  function deleteContact($intContactID){
    $arrContacts = array();
    $intUserID = $_POST["userID"];
    global $connection;
    $strSQL = "DELETE FROM asikpo_270Project.tblContact
                WHERE intUserID = '$intUserID'
                AND intContactID = '$intContactID'"; //find values that contains "FirstName" in it
    $rsResult = mysqli_query($connection, $strSQL);
      //echo "Query Used: ". $strSQL;
    if($rsResult){
        echo '<script>alert("Contact was successfully deleted!")</script>';
    }
    else{
      echo '<script>alert("There was a problem deleting that record\nTry again!")</script>';
    }
   // echo "Given ID: ". $intUserID;
      // return $arrContacts;  
  }      

  ?>
