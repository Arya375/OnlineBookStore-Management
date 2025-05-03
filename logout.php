<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the appropriate login page or homepage
header("Location: index.php");
exit();
?>