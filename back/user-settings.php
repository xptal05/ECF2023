<?php
if (session_status() == PHP_SESSION_NONE) {
    include 'session.php';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs</title>
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
            <button class="svg-btn" id="prevPage">Previous</button>
            <span id="currentPage">Page 1</span>
            <span id="totalPages">of 1</span>
            <button class="svg-btn" id="nextPage">Next</button>
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
                <div class="span-6 new-form-input password-input"><label>Password</label>
                    <input id="PasswordInput" type="password" value="" required>
                    <img src="./src/eye.svg">
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
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="button-container span-2-end">
                    <button class="btn" type="reset" id="close-btn">Annuler</button>
                    <button class="btn" type="submit">Sauvegarder</button>
                </div>
            </form>
        </div>
    </section>
    <script src="./script-dropdowns.js"></script>

    <script src="./scripts/script-mapping.js"></script>
    <script src="./scripts/script-fetchData.js"></script>
    <script src="./scripts/script-filterActions.js"></script>
    <script src="./scripts/script-updateData.js"></script>

    <script src="./scripts/script-popups.js"></script>
    <script src="./scripts/script-postDb.js"></script>
    <script src="./scripts/script-notifications.js"></script>

    <script src="./components/script-new-item.js"></script>
    <script src="./components/menu.js"></script>
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

        const userForm = document.getElementById('userForm')

        // Add a submit event listener to the form
        userForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const form = document.querySelector('.form-new-container')

            if (formvalidation() == true) {
                arraypush()
                form.classList.toggle('on')
            }; // Call the arraypush function when the form is submitted
        });
        let isPopupOpen = false

        function attachActionBtnListeners(filteredData) {

            console.log('array is')
            const actionBtns = document.querySelectorAll('a.actionbtn');

            // Add a click event listener to the table rows (or the "Modifier" links)
            actionBtns.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const itemId = parseInt(e.currentTarget.getAttribute('href').split('=')[1], 10);
                    const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');
                    console.log(actionBtn)
                    console.log('item id', itemId)

                    // Fetch the corresponding data from your 'data' array
                    const selectedItem = filteredData.find(item => parseInt(item[customMappings[currentURL].headers["Actions"]],10) === itemId);
                    console.log(selectedItem)

                    

                    if (selectedItem) {

                        if (actionBtn == 'modify') {
                            if (isPopupOpen == true) {
                                popup.innerHTML = ''; // Close the popup window
                                isPopupOpen = false
                            }
                            modifyUserPopup(selectedItem)

                        } else if (actionBtn == 'delete') {
                            if (isPopupOpen == true) {
                                popup.innerHTML = ''; // Close the popup window
                                isPopupOpen = false
                            }
                            //deletePopup(selectedItem, tableId, name, idKey)
                            confirmationPopup(selectedItem)
                        }

                        if (isPopupOpen == true) {
                            // Attach the closeAction event listener
                            const closeAction = document.getElementById('close');
                            closeAction.addEventListener('click', () => {
                                popup.innerHTML = ''; // Close the popup window
                                isPopupOpen = false

                            });
                        }

                    }
                })
            })
        }
    </script>

</body>

</html>