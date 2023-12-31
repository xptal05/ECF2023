<?php
if (session_status() == PHP_SESSION_NONE) {
    include './functions/session.php';
}

// Table array
$tables = [
    ['name' => 'Services','db_name' => 'Services', 'columns' => ['Icon', 'Titre', 'Description', 'Ordre'], 'id' => 'servicetbl', 'span' => 'span-12'],
    ['name' => 'Horaires','db_name' => 'Hours', 'columns' => ['Text'], 'id' => 'hourstbl', 'span' => 'span-6 row-2'],
    ['name' => 'Contact','db_name' => 'Contact', 'columns' => ['Text', 'Categorie'], 'id' => 'contacttbl', 'span' => 'span-6'],
    ['name' => 'Adresse','db_name' => 'Address', 'columns' => ['Text', 'Categorie'], 'id' => 'addresstbl', 'span' => 'span-6'],
    ['name' => 'A propos','db_name' => 'About', 'columns' => ['Text'], 'id' => 'abouttbl', 'span' => 'span-4'],
    ['name' => 'Raison','db_name' => 'Reasons', 'columns' => ['Text', 'Cathgorie', 'Ordre'], 'id' => 'reasonstbl', 'span' => 'span-8'],
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info website</title>
    <link rel="stylesheet" href="./assets/style/style.css">
    <link rel="stylesheet" href="./assets/style/style-svg-btn.css">
<style>
    table th:last-child{
        min-width: 125px;
    }
</style>
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
        <?php foreach ($tables as $table): ?>
                <div class="tbl <?php echo $table['span'] ?>">
                    <div class="table-header">
                        <h2><?php echo $table['name']; ?></h2>
                        <a class="actionbtn svg-btn add" href="?add=<?php echo $table['db_name']; ?>" title="Ajouter">+</a>
                    </div>
                    <table>
                        <thead>
                            <?php foreach ($table['columns'] as $column): ?>
                                <th><?php echo $column; ?></th>
                            <?php endforeach; ?>
                            <th></th>
                        </thead>
                        <tbody id="<?php echo $table['id']; ?>"></tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script src="./assets/js/script-popups.js"></script>
    <script src="./assets/js/script-postDb.js"></script>
    <script src="./assets/js/script-notifications.js"></script>
    <?php adminAccess() ?>
    <script src="./assets/js/script-fetchData.js"></script>
    <script src="./assets/js/script-web-info.js"></script>
    <script src="./components/menu.js"></script>
</body>

</html>