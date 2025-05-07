<?php
session_start();
require 'db.php'; // Change to require for critical dependency
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: index.html"); // Redirect to login page
exit();
?>
