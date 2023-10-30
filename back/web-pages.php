<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/style-svg-btn.css">

</head>

<body class="application-window">
    <section id="notifications"></section>

    <section class="nav">
        <?php include_once "./components/menu.php" ?>
    </section>
    <section id="popup"></section>

    <section class="application">
        <h1>Gestion du site</h1>
        <div class="application-body">
            <div class="tbl span-12">
                <div class="table-header">
                    <h2>Les Services</h2>
                    <a class="actionbtn svg-btn add" href="?add=Services" title="Ajouter">+</a>
                </div>
                <table>
                    <thead>
                        <th>IMG</th>
                        <th>HEADING</th>
                        <th>TEXT</th>
                        <th>ORDER</th>
                    </thead>
                    <tbody id="servicetbl"></tbody>
                </table>

            </div>
            <div class="tbl span-6 row-2">
                <div class="table-header">
                    <h2>Les Horaires</h2>
                    <a class="actionbtn svg-btn add" href="?add=Hours" title="Ajouter">+</a>
                </div>
                <table>
                    <thead>
                        <th>TEXT</th>
                        <th>ORDER</th>
                    </thead>
                    <tbody id="hourstbl"></tbody>
                </table>
            </div>
            <div class="tbl span-6">
                <div class="table-header">
                    <h2>Le Contact</h2>
                    <a class="actionbtn svg-btn add" href="?add=Contact" title="Ajouter">+</a>
                </div>
                <table>
                    <thead>
                        <th>TEXT</th>
                        <th>CATEGORY</th>
                        <th>ORDER</th>
                    </thead>
                    <tbody id="contacttbl"></tbody>
                </table>
            </div>
            <div class="tbl span-6">
                <div class="table-header">
                    <h2>L 'Addresse</h2>
                    <a class="actionbtn svg-btn add" href="?add=Address" title="Ajouter">+</a>
                </div>
                <table>
                    <thead>
                        <th>TEXT</th>
                        <th>CATEGORY</th>
                        <th>ORDER</th>
                    </thead>
                    <tbody id="addresstbl"></tbody>
                </table>
            </div>
        </div>
    </section>
    <script src="./scripts/script-popups.js"></script>
    <script src="./scripts/script-postDb.js"></script>
    <script src="./scripts/script-notifications.js"></script>
    <script src="script-web-info.js"></script>
    <script src="./components/menu.js"></script>
    <script>

    </script>
</body>

</html>