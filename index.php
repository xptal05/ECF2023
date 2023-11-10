<?php 
// Start or resume the session
session_start();

// Generate and store the CSRF token in the user's session
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parrot garage</title>
    <link rel="stylesheet" href="./assets/style/style.css">
</head>

<body>
    <section id="notifications"></section>
    <?php include_once "./components/header.php";
    ?>
    <div class="landing-background"></div>
    <div class="hero-section">
        <div class="hero-section-content">
            <h1>GARAGE V. PARROT</h1>
            <h2>Des experts passionnés au service de votre voiture</h2>
            <div class="hero-cta">
                <a class="btn" href="#contact">contactez nous</a>
                <a class="btn no-border" href="#service-section">en savoir plus</a>
            </div>
        </div>
    </div>
    <div class="service-section" id="service-section">
        <h2 class="uppercase">nos <span class="primary">services</span></h2>
        <div class="services-container">
            <?php

            $mappedAndOrderedData = mapAndOrderData($webPageInfoData);
            $serviceItems = $mappedAndOrderedData['Service'];

            // Create an associative array to map icons by associated_to_info
            $iconMap = [];
            foreach ($icons as $icon) {
                $iconMap[$icon['associated_to_info']] = $icon;
            }

            // Create an array to keep track of processed orders
            $processedOrders = [];

            foreach ($serviceItems as $item) {
                $order = $item['order'];

                // Check if this order has already been processed
                if (!in_array($order, $processedOrders)) {

                    // Find the items with type 'heading' and 'text' that match the current order
                    $headingItem = null;
                    $textItem = null;

                    foreach ($serviceItems as $subItem) {
                        if ($subItem['order'] === $order) {
                            if ($subItem['category'] === 'heading') {
                                $headingItem = $subItem;
                            } elseif ($subItem['category'] === 'text') {
                                $textItem = $subItem;
                            }
                        }
                    }

                    // Get the associated icon for the current item
                    $associatedToInfo = $item['id_info'];
                    $icon = $iconMap[$associatedToInfo]['link'];

                    // Output the 'heading' and 'text' for the current order along with the icon
                    echo '<div class="service-container">
                        <div class="service-icon"><img src="' . str_replace('../', './', $icon) . '" alt="icon"></div>
                        <div class="service-text-section">
                            <div class="service-title uppercase">' . $headingItem['text'] . '</div>
                            <div class="service-description">' . $textItem['text'] . '</div>
                        </div>
                    </div>';

                    // Mark this order as processed
                    $processedOrders[] = $order;
                }
            }

            ?>
        </div>
    </div>
    <section class="landing-section">
        <div class="section-divider"></div>
        <div class="about-section">
            <div class="about-container">
                <h2 class="uppercase primary">a propos</h2>
                <?php $aboutItems = $mappedAndOrderedData['About'];
                echo '<p>' . $aboutItems[0]['text'] . '</p>';
                ?>
            </div>
        </div>
    </section>
    <section class="landing-section">
        <div class="section-divider"></div>
        <div class="why-us-section">
            <div class="why-us-container">
                <div class="why-us-text-container">
                    <h2 class="uppercase">Pourquoi<span class="primary"> notre</span> garage</h2>
                    <?php $reasonItems = $mappedAndOrderedData['Reasons'];
                    $reasonsArray = [];
                    foreach ($reasonItems as $subItem) {
                        if ($subItem['category'] === 'heading') {
                            $reasonsArray[] =   $subItem;
                        } elseif ($subItem['category'] === 'text') {
                            $textItem = $subItem;
                        }
                    }
                    echo '<p>' . $textItem['text'] . '</p>';
                    ?>
                </div>
                <div class="reasons-container">
                    <?php
                    foreach ($reasonsArray as $item) {
                        echo ' 
                        <div class="reason">                       
                            <div class="reason-icon"><img src="./assets/src/check-circle.svg" alt="check-mark-icon" ></img></div>
                            <div class="reason-text">' . $item['text'] . '</div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section class="landing-section">
        <div class="section-divider"></div>
        <div class="feedback-section" id="feedback">
            <h2 class="uppercase">Avis de <span class="primary">nos client</span></h2>
            <div class="feedback-slider">
                <?php
                $feedbackCounter = 0;

                foreach ($feedbacksData as $item) {
                    // Increment the counter for each feedback item
                    $feedbackCounter++;

                    echo '<div class="feedback-container">
                    <div class="rating">' . $item['rating'] . '</div>
                        <p>' . $item['comment'] . '</p>
                        <div class="feedback-name">' . $item['client_name'] . '</div>
                    </div>';
                }
                ?>

            </div>
            <div class="feedback-slider-dots">
                <?php
                for ($i = 0; $i < $feedbackCounter; $i++) {
                    echo '<div class="feedback-dot" data-index="' . $i . '"></div>';
                }
                ?>
            </div>
            <a href="feedback.php" class="btn">ajouter mon témoignage</a>

        </div>
    </section>
    <section class="landing-section">
        <div class="section-divider"></div>
        <div class="contact-section" id="contact">
            <h2 class="uppercase"><span class="primary">contactez</span> nous</h2>
            <?php include_once "./components/contact-form.php" ?>
        </div>
    </section>
    <?php include_once "./components/footer.php" ?>
    <script src="./components/script-menu.js"></script>
        <script src="./assets/js/script-contact.js"></script>
        <script src="./assets/js/notifications.js"></script>
    <script>
        //add message event
        messageEvent()

        //FEEDBACK STARS
        const ratings = document.querySelectorAll('.feedback-container .rating')

        ratings.forEach(rating => {
            const stars = convertToStars(Number(rating.innerText))
            rating.innerHTML = `${stars}`
        })

        // Function to convert numeric rating to star icons
        function convertToStars(rating) {
            // Assuming rating is between 1 and 5
            const maxRating = 5;
            let starIcons = '';

            for (let i = 1; i <= maxRating; i++) {
                if (i <= rating) {
                    starIcons += '★'; // Add a filled star for each full rating
                } else {
                    starIcons += '☆'; // Add an empty star for the remaining
                }
            }
            return starIcons;
        }

        //FEEDBACK NAVIGATION
        const slider = document.querySelector(".feedback-slider");
        const dots = document.querySelectorAll(".feedback-dot");
        const feedbackContainers = slider.querySelectorAll(".feedback-container");
        let currentIndex = 0;

        // Add a click event listener to each dot
        dots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                // Update the currentIndex to the clicked dot's index
                currentIndex = index;
                // Calculate the offset to slide the container to the center
                const containerWidth = feedbackContainers[currentIndex].offsetWidth;
                const gap = 45
                const screenWidth = window.innerWidth; // Get the screen width
                const offset = ((containerWidth + gap) / 2) * (feedbackContainers.length + 1 - (2 * (currentIndex + 1)))
                slider.style.transform = `translateX(${offset}px)`;

                // Update the dot styles (e.g., add a "selected" class)
                dots.forEach((dot) => dot.classList.remove("selected"));
                dot.classList.add("selected");
            });
        });

        // On window load, select the middle dot
        window.addEventListener("load", () => {
            const middleIndex = Math.floor(dots.length / 2)-1; // Calculate the middle dot's index
            dots[middleIndex].click(); // Trigger a click event on the middle dot
        });
    </script>

</body>

</html>