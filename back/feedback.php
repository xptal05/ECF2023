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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/style-svg-btn.css">
</head>

<body class="application-window">
    <section id="notifications"></section>

    <section class="nav">
        <?php include_once "./components/menu.php";
        $jsonData = json_encode($_SESSION["user_id"]);
        echo "<script>var UserId = {$jsonData};</script>";
        $template = "This is the dynamic content from PHP";

        ?>
    </section>
    <section id="popup"></section>

    <section class="application">
        <div class="page-header">
            <div class="header-content">
                <h1>temoignages</h1>
            </div>
            <button class="svg-btn add" id="new-item" title="Ajouter un témoignage">+</button>
        </div>
        <div class="tbl-header">
            <div class="filters-applied" id="tags"></div>
            <div class="search-container">
                <input class="search" type="text" id="search" placeholder="chercher témoignages">
            </div>
        </div>
        <div class="tbl">
            <table class="feedback-tbl">
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
            <form id="feedbackForm" class="form-new container">
                <h2 class="span-12">Ajouter un témoignage</h2>
                <div class="span-6 new-form-input"><label>Nom du client</label>
                    <input id="client_nameInput" type="text" value="" required>
                </div>
                <div class="span-3 new-form-input"><label>Statut</label><select id="statusInput" required>
                        <option value="1">Active</option>
                        <option value="2">New</option>
                        <option value="6">Archived</option>
                        <option value="7">Rejected</option>
                    </select>
                </div>
                <div class="span-2 new-form-input"><label>Note</label>
                    <input id="ratingInput" type="number" value="" required hidden>
                    <div class="rating">
                        <div class="star">☆</div>
                        <div class="star">☆</div>
                        <div class="star">☆</div>
                        <div class="star">☆</div>
                        <div class="star">☆</div>
                    </div>
                </div>
                <div class="span-12 new-form-input">
                    <label>Commentaire</label>
                    <textarea id="commentInput" rows="4" cols="100" required></textarea>
                </div>

                <div class="button-container span-2-end">
                    <button class="btn" type="reset" id="close-btn">Annuler</button>
                    <button class="btn" type="submit">Sauvegarder</button>
                </div>
            </form>
        </div>
    </section>
    <script src="./scripts/script-mapping.js"></script>
    <script src="./scripts/script-createTag.js"></script>
    <script src="./scripts/script-eventListeners.js"></script>
    <script src="./scripts/script-updateData.js"></script>
    <script src="./scripts/script-fetchData.js"></script>
    <script src="./scripts/script-popups.js"></script>
    <script src="./scripts/script-postDb.js"></script>
    <script src="./scripts/script-notifications.js"></script>
    <script src="./event/script-messagesFeedback.js"></script>
    <script src="./components/script-new-item.js"></script>
    <script src="./components/menu.js"></script>

    <script>
        //STARS
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput')

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                fillStars(stars, index);
                console.log(index + 1)
                ratingInput.value = index + 1
            });
        });

        function fillStars(stars, selectedIndex) {
            for (let i = 0; i <= selectedIndex; i++) {
                stars[i].innerText = '★';
            }

            for (let i = selectedIndex + 1; i < stars.length; i++) {
                stars[i].innerText = '☆';
            }
        }

        const feedbackForm = document.getElementById('feedbackForm')
        // Add a submit event listener to the form
        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formType = "feedback"
            if (formvalidation(formType) == true) {
                let formData = {}
                formData.userId = UserId

                // Iterate through all input and select elements
                feedbackForm.querySelectorAll('input, select, textarea').forEach(input => {
                    // Handle other input and select elements
                    formData[input.id.replace("Input", "")] = input.value;
                });
                pushNewFeedback(formData)
                form.classList.toggle('on')
            }; // Call the arraypush function when the form is submitted
        });
    </script>
</body>

</html>