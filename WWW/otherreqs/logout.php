<?php
session_start();

// destroys the current session to remove stored user data
// this logs the user out by clearing userid and username
session_destroy();

// after logging out, redirect the user back to the homepage
header("Location: /WWW/Pages/start.php");
?>
