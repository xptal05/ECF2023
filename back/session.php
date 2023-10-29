<?php 
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect to the login page or another page as needed
    exit();
} 
// Check if the user has been inactive for 30 minutes (1800 seconds)
$inactiveTimeout = 1800; // 30 minutes in seconds
if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"]) > $inactiveTimeout) {
    // User has been inactive for too long; log them out
    session_unset();
    session_destroy();
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Update the last activity timestamp
$_SESSION["last_activity"] = time();