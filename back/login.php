<?php
session_start();
include 'func.php';
login()

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-page">
        <div class="background-img"></div>
        <div class="logo"><img src="../src/logo.svg"></div>
        <form class="login-form-container" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-field">
                <label for="login">Login</label>
                <input type="text" name="login" id="login" required>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <div class="password-input"><input type="password" name="password" id="password" required><img src="./src/eye.svg"></div>
            </div>
            <input type="hidden" name="session_token" value="<?php echo $_SESSION['session_token']; ?>">
            <input type="submit" class="btn front" value="se connecter">
        </form>

        <script>
            //Event Listener Password btn
            const revealPassword = document.querySelector('.password-input img');
            const passwordInput = document.querySelector('.password-input input')

            revealPassword.addEventListener('click', () => {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text'; // Change input type to text to reveal the password
                } else {
                    passwordInput.type = 'password'; // Change input type back to password
                }
            })
        </script>
</body>

</html>