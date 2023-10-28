<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .form-new {
            padding-top: 170px;
        }

        .rating {
            display: flex;
        }
    </style>
</head>

<body>
<section id="notifications"></section>
    <?php include_once "./components/header.php" ?>
    <div class="form-new-container on">
        <form id="feedbackForm" class="form-new container">
            <h2 class="span-12">Ajouter un témoignage</h2>
            <div class="span-6"><label>Nom</label>
                <input id="client_nameInput" type="text" value="" required>
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
                <textarea id="commentInput" rows="4" cols="50" required></textarea>
            </div>
            <div class="span-6"><label>Statut</label><select id="statusInput" hidden>
                    <option value="2">New</option>
                </select>
            </div>
            <div class="button-container span-2-end">
                <button class="btn" type="reset" id="reset-btn">Annuler</button>
                <button class="btn" type="submit">Envoyer</button>
            </div>
        </form>
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


        /*
            for (let i = 1; i <= maxRating; i++) {
                if (i <= rating) {
                    starIcons += '★'; // Add a filled star for each full rating
                } else {
                    starIcons += '☆'; // Add an empty star for the remaining
                }
            }
            */

        function pushNewFeedback(formData, feedbackForm) {

            const phpScriptURL = '../BACK/func-one.php?action=newFeedback';
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
                    //notification + clear form
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