<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <section id="notifications"></section>
    <?php include_once "./components/header.php" ?>
    <div class="feedback-page">
        <div class="feedback-img"><img src="./src/feedback-img-1.png"></div>
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
            <div class="button-container span-2-end">
                <button class="btn" type="reset" id="reset-btn">Annuler</button>
                <button class="btn" type="submit">Envoyer</button>
            </div>
        </form>
        <div class="feedback-img"><img src="./src/feedback-img-2.png"></div>
    </div>
    <?php include_once "./components/footer.php" ?>
    <script src="script.js"></script>
    <script>
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

        function notificationsServeur(data) {
            notifications.classList.toggle('on')
            if (data['message'].startsWith('Erreur')) {
                notifications.classList.add('error')
            } else if (data['message'].startsWith('Succès')) {
                notifications.classList.add('success')
            }
            notifications.innerHTML = `${data['message']}<div class="notification-progress-bar"></div>`
            setTimeout(function() {
                notifications.className = '';
            }, 8000);
        }

        function pushNewFeedback(formData, feedbackForm) {

            const phpScriptURL = './back/db_query.php?action=newFeedback';
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
                    // Handle the response from the server
                    // Show a success message or redirect to a success page
                    console.log(data)
                    notificationsServeur(data)
                    feedbackForm.reset()
                    stars.forEach(star => {
                        star.innerText = `☆`
                    })
                })

                .catch((error) => {
                    console.error("Error:", error);
                    console.log(data)

                });
        }

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
            return true
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
                    console.log(formData)
                    pushNewFeedback(formData, feedbackForm)
                };
            } // Call the arraypush function when the form is submitted
        });

        const resetButton = document.getElementById('reset-btn'); // Replace 'resetButton' with the actual ID of your reset button

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