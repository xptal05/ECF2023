<?php
if (session_status() == PHP_SESSION_NONE) {
    include './functions/session.php';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
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
        <div class="page-header">
            <div class="header-content">
                <h1>Vehicules</h1>
            </div>
            <a class="svg-btn add" href="./vehicle-form.php" title="Ajouter un vehicule">+</a>
        </div>
        <div class="tbl-header">
            <div class="filters-applied" id="tags"></div>
            <div class="search-container">
                <input class="search" type="text" id="search" placeholder="chercher vehicules">
            </div>
        </div>
        <div class="tbl">
            <table class="vehicles-tbl">
                <thead id="tableHeaders"></thead>
                <tbody id="list"></tbody>
            </table>
        </div>
        <div class="table-pagination">
            <button class="svg-btn" id="prevPage">Previous</button>
            <span id="currentPage">Page 1</span>
            <span id="totalPages">of 1</span>
            <button class="svg-btn" id="nextPage">Next</button>
        </div>
    </section>
    <script src="./assets/js/script-mapping.js"></script>
    <script src="./assets/js/script-fetchData.js"></script>
    <script src="./assets/js/script-filterActions.js"></script>
    <script src="./assets/js/script-updateData.js"></script>

    <script src="./assets/js/script-popups.js"></script>
    <script src="./assets/js/script-postDb.js"></script>
    <script src="./assets/js/script-notifications.js"></script>
    <script src="./components/menu.js"></script>
    <script>


        function attachActionBtnListeners(filteredData) {
            let isPopupOpen = false;
            const actionBtns = document.querySelectorAll('a.actionbtn');
            actionBtns.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    const itemId = parseInt(e.currentTarget.getAttribute('href').split('=')[1], 10);
                    const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

                    let idKey = 'id'; 

                    const selectedItem = filteredData.find((item) => parseInt(item[idKey],10) === itemId);

                    if (selectedItem) {
                        if (actionBtn === 'archiv') {
                            e.preventDefault();
                            if (isPopupOpen) {
                                popup.innerHTML = ''; // Close the popup window
                                isPopupOpen = false;
                            }
                            // Call the webInfoPopup function with the selected item
                            let status = "6"
                            archiveMessageFeedbackPopup(selectedItem, idKey, status);
                        } else {
                            console.log('Item not found');
                        }
                    }
                });
            });
        }
    </script>
</body>

</html>