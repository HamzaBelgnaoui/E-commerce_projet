<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the homepage in a subdirectory
header("Location: prject2_Ecommerce.html");
exit();
