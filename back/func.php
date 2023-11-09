<?php

function login()
{
    require_once "./config/db.php";
    require_once "./config/jwt.php";
    $pdo = connectToDatabase($dbConfig);

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $inputUsername = $_POST["login"];
            $inputPassword = trim($_POST["password"]);

            // Verify the session token before processing the request
            if (isset($_POST["session_token"]) && $_POST["session_token"] === $_SESSION["session_token"]) {
                $statement = $pdo->prepare("SELECT * FROM users WHERE email = :username");
                $statement->bindParam(':username', $inputUsername, PDO::PARAM_STR);

                // Query to check if the provided username exists in the database
                if ($statement->execute()) {
                    $user_login = $statement->fetch(PDO::FETCH_ASSOC);
                    if ($user_login && password_verify($inputPassword, $user_login["password"])) {
                        // Password is correct, check user role
                        if ($user_login["role"] == 1) {
                            $_SESSION["admin"] = $user_login["role"];
                        }
                        $_SESSION["user_id"] = $user_login["id_user"];
                        $_SESSION["user_name"] = $user_login["first_name"];
                        $_SESSION["csrf_token"] = $_POST["session_token"];

                        $jwtToken = generateJWT($_SESSION["user_id"], $_SESSION["user_name"]);
                        error_log("JWT Token: $jwtToken"); // Check your server logs

                        header("Authorization: Bearer $jwtToken");

                       header("Location: index.php");
                        exit();
                    } else {
                        echo '<div class="error">Incorrect username or password. Please try again.</div>';
                    }
                }
            } else {
                echo '<div class="error">CSRF Token validation failed. Please try again.'.$_SESSION["session_token"] .'post'. $_POST["session_token"]. '</div>';
            }
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
}




function logout() {
    // Clear and destroy the session
    session_start();
    $_SESSION = array();
    session_unset();
    session_destroy();
    // Add a JavaScript delay and redirection
    echo '<script>
        setTimeout(function() {
            window.location.href = "login.php";
        }, 10); 
    </script>';
}

function admin_menu(){
    if (isset($_SESSION["admin"])) {
        echo '<div class="menu-section three">
        <div class="menu-section-header">Réglages</div>
        <a href="./web-pages.php">Pages web</a>
        <a href="./user-settings.php">Utilisateur</a>
        <a href="./settings.php">Autres</a>
    </div>';
    }
}
