
<?php
session_start();

// Remove the login session
$_SESSION = [];
session_destroy();

// Return to the home page
header("Location: index.php");
exit;
?>
