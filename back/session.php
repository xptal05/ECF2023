<?php
require_once "config/jwt.php";
session_start();

// Generate a unique session token
$session_token = bin2hex(random_bytes(32));
$_SESSION["session_token"] = $session_token;

/* Check if the user is logged in
if (isset($_SESSION["user_id"])) {
    // Generate JWT token
    $jwtToken = generateJWT($_SESSION["user_id"], $_SESSION["user_name"]);

    // Set the Authorization header
    header("Authorization: Bearer $jwtToken");
    // Check if user is authenticated otherwise redirect to login
    authenticateUser();
}*/


// Check if the user has been inactive for 30 minutes (1800 seconds)
$inactiveTimeout = 1800; // 30 minutes in seconds
if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"]) > $inactiveTimeout) {
    // User has been inactive for too long-> log out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// VERIFICATION DE NAVIGATEUR + IP Address
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('location: login.php');
    exit();
}

// Update the last activity timestamp
$_SESSION["last_activity"] = time();

//CHECK IF THE USER ROLE IS ADMIN FOR PAGES THAT REQUIRE ADMIN ACCESS
function adminAccess() {
    $adminPages = ['web-pages.php', 'user-settings.php', 'settings.php'];

    if (in_array(basename($_SERVER['PHP_SELF']), $adminPages)) {
        // Check if the session user role is equal to admin
        if ($_SESSION['admin'] != 1) {
            echo '
                <script>
                    const data = { message: "Erreur : ACCESS PAS AUTHORISE" };
                    notificationsServeur(data);
                </script>';
            exit();
        }
    }
}






