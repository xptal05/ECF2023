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
    <section class="nav">
        <?php include_once "./components/menu.php" ?>
    </section>
    <section id="popup"></section>
    <section class="application">
        <h1>Gestion des utilisateurs</h1>
        <div class="tbl-header">
            <div class="filters-applied" id="tags"></div>
            <div class="search-container">
                <input class="search" type="text" id="search" placeholder="chercher utilisateur">
            </div>
        </div>
        <div class="tbl">
            <table class="users-tbl">
                <thead id="tableHeaders"></thead>
                <tbody id="list"></tbody>
            </table>
        </div>
        <div class="table-pagination">
            <button id="prevPage">Previous</button>
            <span id="currentPage">Page 1</span>
            <span id="totalPages">of 1</span>
            <button id="nextPage">Next</button>
        </div>
        <div class="form-new-container">
            <button class="btn" id="new-item">Ajouter un utilisateur</button>
            <form id="userForm" class="form-new container">
                <h2 class="span-12">Ajouter un utilisateur</h2>
                <div class="span-6 new-form-input">
                    <label>Nom</label>
                    <input id="NomInput" type="text" value="" required>
                </div>
                <div class="span-6 new-form-input"><label>Prénom</label>
                    <input id="PrénomInput" type="text" value="" required>
                </div>
                <div class="span-6 new-form-input"><label>Email</label>
                    <input id="EmailInput" type="email" value="" required>
                </div>
                <div class="span-6 new-form-input"><label>Password</label>
                    <input id="PasswordInput" type="password" value="" required>
                </div>
                <div class="span-6 new-form-input"><label>Droits</label><select id="DroitsInput" required>
                        <option value="1">Admin</option>
                        <option value="2">Employee</option>
                    </select></div>
                <div class="span-6 new-form-input"><label>Statut</label><select id="StatutInput" required>
                        <option value="1">Active</option>
                        <option value="6">Archived</option>
                    </select>
                </div>
                <div class="button-container span-2-end">
                    <button class="btn" type="reset" id="close-btn">Annuler</button>
                    <button class="btn" type="submit">Sauvegarder</button>
                </div>
            </form>
        </div>
    </section>
    <script src="./script-dropdowns.js"></script>
    <script src="./scripts/script-mapping.js"></script>

    <script src="./scripts/script-createTag.js"></script>

    <script src="./scripts/script-eventListeners.js"></script>

    <script src="./scripts/script-updateData.js"></script>

    <script src="./scripts/script-fetchData.js"></script>
    <script src="./scripts/script-popups.js"></script>
    <script src="./scripts/script-postDb.js"></script>

    <script src="./scripts/script-notifications.js"></script>

    <script src="./components/script-new-item.js"></script>
    <script src="./event/script-users.js"></script>
    <script src="./components/menu.js"></script>

</body>

</html>