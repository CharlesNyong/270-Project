<?php
session_start();
echo "<center><h1 style='margin-bottom:20px;'>Error: You don't have access to view the requested page!!</h1></center>";
session_destroy();
header( "refresh:0.5; url=loginPage.php" ); 
?>
