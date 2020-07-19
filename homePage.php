<?php
include("connection.php");
session_start();
$arrAllContacts = array();

//var_dump($_SESSION);

if($_POST["logOut"]){
  //setcookie("blnLogOut", 1);
  session_destroy();
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
//var_dump($_COOKIE);
if($_SESSION["blnAuthenticated"] != 1){  
  header("Location: http://asikpo.myweb.cs.uwindsor.ca/60270/Project/loginPage.php");
}
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
  if(!deleteContact($_POST["intContactID"])){
    echo '<script>alert("There was a problem deleting that record\nTry again!")</script>';
  }
  $arrAllContacts = displayAllContactsForUser($_POST["userID"]); // get updated contact list
  //echo "In delete case";
}
else if(isset($_POST["userID"]) && $_POST["blnGetContact"] != 1){
	$arrAllContacts = displayAllContactsForUser($_POST["userID"]);
}
if($_POST["blnGetContact"]){
	$arrAllContacts = getContact($_POST["nameSearch"]);
  if(empty($arrAllContacts)){
    $rsSearchResult = "not found";
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
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .tableDataXX{
      font-weight:normal; 
      font-size:16px; 
      color: #3366BB;
    }

    .theader-color{
      background-color: #E5E4E2;
      font-size: 1.2em;
    }
  </style>
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
      <form id="filterFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
        <fieldset>
          <legend>Search</legend>
          <label for="nameSearch">Name:</label>
          <input type="text" id="nameSearch" name="nameSearch" onkeypress="callFunc(event)"></input> <input type="button" value="Find" onclick="getContact()"/>
          <!-- <input type="button" value="All Contacts" onclick="displayAllContacts()"/> -->
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
             <th class="theader-color">Name</th>
             <th class="theader-color">Phone Number</th>
             <th class="theader-color">Email</th>
             <th class="theader-color"></th>
          </tr>
       </thead>
       <tbody>
          <?if(!empty($arrAllContacts)){ 
              foreach ($arrAllContacts as $intContactID => $arrContact) {?>
                    <tr>
                    <?foreach ($arrContact as $mixKey => $mixData) {
                        $strLink = "viewDetails.php?UserID=".$arrContact["intUserID"]."&ContactID=".$arrContact["intContactID"];?>
                        <?if($mixKey != "intUserID" && $mixKey != "intContactID"){?>
                          <td class="tableDataXX" title="Click to go to the details page" onclick="redirect('<?echo $strLink?>')">
                              <?echo $mixData;?>
                          </td>   
                          <?}
                      }?>
                      <td><button  onclick="deleteContact('<?echo $arrContact["intContactID"]?>')">
                        Delete <img src="images/deleteIcon.png" width="25" height="24"/>
                      </button></td>
                    </tr>
                <?}
              }else if(empty($arrAllContacts) && $rsSearchResult == "not found"){?>
                <tr><td colspan="4"><h2>Contact Not Found!</h2></td></tr>
              <?}else{?>
                <tr><td colspan="4"><h2>Oops! you might want to create some contacts!</h2></td></tr>
              <?}?>
       </tbody>
      </table>
      <form id="logOutFrm" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
        <input type="hidden" value="" name="logOut"  id="logOut"/>
       </form> 
 </div>
<br><br><br>
<footer class="container-fluid text-center">
  <address> 
     <p><a href="comment.php">Send comment</a></p>  
     <p>You can contact me @ <b>2263486563</b><p>
    </address>  
</footer>
  
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
        return true;
    }
    else{
      return false;
    }
   // echo "Given ID: ". $intUserID;
      // return $arrContacts;  
  }      

  ?>
