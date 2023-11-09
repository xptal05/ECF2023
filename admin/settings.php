<?php
if (session_status() == PHP_SESSION_NONE) {
    include './functions/session.php';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglages</title>
    <link rel="stylesheet" href="./assets/style/style.css">
    <link rel="stylesheet" href="./assets/style/style-svg-btn.css">

</head>

<body class="application-window">
<section id="notifications"></section>
    <section class="nav">
        <?php include_once "./components/menu.php" ?>
    </section>

    <section id="popup"></section>
    <section class="application">
        <h1>REGLAGES</h1>
            <div id="dropdownsContainer"></div>
        </div>
    </section>
    <script src="./assets/js/script-notifications.js"></script>
    <?php adminAccess() ?>
    <script src="./assets/js/script-popups.js"></script>
    <script src="./assets/js/script-postDb.js"></script>
    <script src="./assets/js/script-fetchData.js"></script>
    <script src="./assets/js/script-dropdowns.js"></script>
    <script src="./components/menu.js"></script>
    <script src="./assets/js/script-settings.js"></script>

</body>

</html>