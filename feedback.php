<?php 
// Start or resume the session
session_start();

if (!isset($_SESSION['csrf_token'])) {
    // Generate and set the session token if not already set
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temoignages</title>
    <link rel="stylesheet" href="./assets/style/style.css">
</head>

<body>
    <section id="notifications"></section>
    <?php include_once "./components/header.php" ?>
    <div class="feedback-page">
        <div class="feedback-img"><img src="./assets/src/feedback-img-1.png"></div>
        <form id="feedbackForm" class="form-new container feedback-form">
            <h2 class="span-12 primary uppercase">Ajouter un témoignage</h2>
            <div class="span-6"><label>Nom</label>
                <input id="client_nameInput" type="text" value="" required class="input-front" maxlength="150">
            </div>
            <div class="span-6"><label>Note</label>
                <div class="rating">
                    <div class="star">☆</div>
                    <div class="star">☆</div>
                    <div class="star">☆</div>
                    <div class="star">☆</div>
                    <div class="star">☆</div>
                </div>
                <input id="ratingInput" type="number" value="" hidden>
            </div>
            <div class="span-6"><label>Commentaire</label>
                <textarea id="commentInput" rows="5" cols="50" required class="input-front"></textarea>
            </div>
            <select id="statusInput" hidden>
                <option value="2">New</option>
            </select>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="button-container span-2-end">
                
                <button class="btn" type="reset" id="reset-btn">Annuler</button>
                <button class="btn" type="submit">Envoyer</button>
            </div>
        </form>
        <div class="feedback-img"><img src="./assets/src/feedback-img-2.png"></div>
    </div>
    <?php include_once "./components/footer.php" ?>
    <script src="./components/script-menu.js"></script>
    <script src="./assets/js/notifications.js"></script>
    <script>
        //convert number into stars
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput')

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                fillStars(stars, index);
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

        function pushNewFeedback(formData, feedbackForm) {
            const phpScriptURL = './admin/functions/db_query.php?action=newFeedback';
            formData.action = 'newFeedback'

            // Send an AJAX request to update the database
            fetch(phpScriptURL, {
                    method: 'POST',
                    body: JSON.stringify(formData), // Send data as JSON
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })

                .then((response) => response.json()) // Assuming the server returns JSON
                .then((data) => {
                    notificationsServeur(data)
                    feedbackForm.reset()
                    stars.forEach(star => {
                        star.innerText = `☆`
                    })
                })

                .catch((error) => {
                    console.error("Error:", error);
                });
        }

        //the stars in the feedback form
        function isRatingSelected(stars) {
            // Check if at least one star is selected
            for (const star of stars) {
                if (star.innerText === '★') {
                    return true; // At least one star is selected
                }
            }
            return false; // No star is selected
        }

        function formvalidation() {
            let valid = true
            const clientNameInput = document.getElementById('client_nameInput');
            const ratingInput = document.getElementById('ratingInput');
            const commentInput = document.getElementById('commentInput');

            // Validate client name (required, max length)
            if (clientNameInput.value.trim() === '' || clientNameInput.value.length > 150) {
                alert('Veuillez entrer votre nom.');
                valid = false
            }

            // Validate rating (at least one star required)
            if (ratingInput.value < 1) {
                alert('Veuillez sélectionner une évaluation d\'au moins 1 étoile.');
                valid = false
            }

            // Validate comment (required)
            if (commentInput.value.trim() === '') {
                alert('Veuillez entrer un commentaire.');
                valid = false
            }
            return valid
        }
        const feedbackForm = document.getElementById('feedbackForm')
        
        // Add a submit event listener to the form
        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault();

            if (!isRatingSelected(stars)) {
                alert('Please select a star rating before submitting.');
            } else {

                if (formvalidation() == true) {
                    let formData = {}
                    formData.userId = ""

                    // Iterate through all input and select elements
                    feedbackForm.querySelectorAll('input, select, textarea').forEach(input => {
                        // Handle other input and select elements
                        formData[input.id.replace("Input", "")] = input.value;
                    });
                    pushNewFeedback(formData, feedbackForm)
                };
            } 
        });

        const resetButton = document.getElementById('reset-btn');

        resetButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the form from submitting

            // Reset the form
            feedbackForm.reset();
            stars.forEach(star => {
                star.innerText = `☆`
            })
        });
    </script>
</body>

</html>