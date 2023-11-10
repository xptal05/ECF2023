<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parrot Catalogue</title>
    <link rel="stylesheet" href="./assets/style/style.css">
    <style>

    </style>
</head>

<body>
    <section id="notifications"></section>
    <?php include_once "./components/header.php";
    include_once "./functions/func.php";
    $minMaxValues = fetch_min_max_values_filters();

    $minKm = floor($minMaxValues['MIN(km)'] / 100) * 100;
    $maxKm = ceil($minMaxValues['MAX(km)'] / 100) * 100;
    $minYear = $minMaxValues['MIN(year)'];
    $maxYear = $minMaxValues['MAX(year)'];
    $minPrice = floor($minMaxValues['MIN(price)'] / 100) * 100;
    $maxPrice = ceil($minMaxValues['MAX(price)'] / 100) * 100;

    ?>

    <section id="popup"></section>
    <div class="catalogue">
        <h1 class="uppercase primary">CATALOGUE DE NOS VEHICULES</h1>
        <div class="filter-container">
            <div class="filters-applied">
                <h2>Filtres appliqués:</h2>
                <div id="filter-tags"></div>
            </div>
            <div class="filter-toggle">
                <h2 class="uppercase"><i class="fa-solid fa-sliders"></i>Filtres<h2>
            </div>
            <div class="filter-pop-up-container off">
                <div class="close-btn">X</div>
                <div class="filter-container-filters">
                    <div class="price-filter">
                        <div class="slider">

                            <div class="range-slider">
                                <p>Price Range</p>
                                <span class="rangeValues"></span>
                                <input class="range-min" value="<?php echo $minPrice; ?>" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" step="100" type="range">
                                <input class="range-max" value="<?php echo $maxPrice; ?>" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" step="100" type="range">
                            </div>
                        </div>
                    </div>
                    <div class="year-filter">
                        <div class="slider">

                            <div class="range-slider">
                                <p>Year Range</p>
                                <span class="rangeValues"></span>
                                <input class="range-min" value="<?php echo $minYear; ?>" min="<?php echo $minYear; ?>" max="<?php echo $maxYear; ?>" step="1" type="range">
                                <input class="range-max" value="<?php echo $maxYear; ?>" min="<?php echo $minYear; ?>" max="<?php echo $maxYear; ?>" step="1" type="range">
                            </div>
                        </div>
                    </div>
                    <div class="km-filter">
                        <div class="slider">

                            <div class="range-slider">
                                <p>Km Range</p>
                                <span class="rangeValues"></span>
                                <input class="range-min" value="<?php echo $minKm; ?>" min="<?php echo $minKm; ?>" max="<?php echo $maxKm; ?>" step="100" type="range">
                                <input class="range-max" value="<?php echo $maxKm; ?>" min="<?php echo $minKm; ?>" max="<?php echo $maxKm; ?>" step="100" type="range">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="catalogue-cars-container">

        </div>
        <div class="table-pagination">
            <button class="nav-btn" id="prevPage">Previous</button>
            <div>
                <span id="currentPage">Page 1</span>
                <span id="totalPages">sur 1</span>
            </div>
            <button class="nav-btn" id="nextPage">Next</button>
        </div>

    </div>
    <?php include_once "./components/footer.php";

    ?>
    <script src="./assets/js/notifications.js"></script>
    <script src="./components/script-menu.js"></script>
    <script src="./assets/js/script-contact.js"></script>

    <script>
        const filter_toggle = document.querySelector('.filter-toggle')
        const filter_pop_up = document.querySelector('.filter-pop-up-container')
        const filterCloseBtn = document.querySelector('.filter-pop-up-container .close-btn')

        filterCloseBtn.addEventListener('click', (e) => {
            filter_pop_up.classList.toggle('off')
        })



        filter_toggle.addEventListener('click', () => {
            filter_pop_up.classList.toggle('off')
        })
        // get data -> pass it to filter -> populate
        //on filter -> filter data -> populate
        // Attach event listeners to pagination controls

        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        let currentPage = 1
        let totalPages = 1

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                filterVehicles(data);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                filterVehicles(data)
            }
        });

        //get the values for the filters
        function getVals() {
            // Get slider values
            let parent = this.parentNode;

            // Determine which slider is being updated
            let rangeType = parent.querySelector("p").textContent;

            let slides = parent.getElementsByTagName("input");
            // Your original code to calculate slide1 and slide2
            let slide1 = parseFloat(slides[0].value);
            let slide2 = parseFloat(slides[1].value);

            // Your desired intervals for rounding
            const minInterval = 100;
            const intervalYear = 1;
            const maxInterval = 100;

            // Apply rounding based on the specific range type
            if (rangeType === "Km Range") {
                slide1 = Math.floor(slide1 / minInterval) * minInterval;
                slide2 = Math.ceil(slide2 / maxInterval) * maxInterval;
            } else if (rangeType === "Year Range") {
                slide1 = Math.floor(slide1 / intervalYear) * intervalYear;
                slide2 = Math.ceil(slide2 / intervalYear) * intervalYear;
            } else if (rangeType === "Price Range") {
                slide1 = Math.floor(slide1 / minInterval) * minInterval;
                slide2 = Math.ceil(slide2 / maxInterval) * maxInterval;
            }

            // Neither slider will clip the other, so make sure we determine which is larger
            if (slide1 > slide2) {
                let tmp = slide2;
                slide2 = slide1;
                slide1 = tmp;
            }

            // Define symbols for different range types
            const symbols = {
                "Price Range": "EUR",
                "Year Range": "",
                "Km Range": "km"
            };

            // Set the appropriate symbol based on the range type
            let symbol = symbols[rangeType];

            // Check if the slider is at its maximum position
            if (slide2 === parseFloat(slides[1].max)) {
                slide2 = Math.ceil(slide2); // Round up to the nearest integer
            }

            // Display the range values with the symbol
            let displayElement = parent.getElementsByClassName("rangeValues")[0];
            displayElement.innerHTML = `${slide1} ${symbol} - ${slide2} ${symbol}`;
        }

        // intitialize the filter values
        window.onload = function() {
            let sliderSections = document.getElementsByClassName("range-slider");
            //get each slider
            for (let x = 0; x < sliderSections.length; x++) {

                let sliders = sliderSections[x].getElementsByTagName("input");

                //get each min and max of each slider
                for (let y = 0; y < sliders.length; y++) {
                    if (sliders[y].type === "range") {
                        // Add an event listener for the input event
                        sliders[y].addEventListener('input', function() {
                            // Call getVals to update the displayed range values
                            getVals.call(this);
                            // Call filterVehicles to filter the data
                            if (typeof data !== "undefined") {
                                filterVehicles(data);
                            }
                        });
                        // Manually trigger the input event first time to display values
                        sliders[y].dispatchEvent(new Event('input'));
                    }
                }
            }
            fetchDataAndRenderList();
        }

    //filter the vehicle data
        function filterVehicles(data) {
            const minKm = document.querySelector('.km-filter .range-min').value;
            const maxKm = document.querySelector('.km-filter .range-max').value;
            const minYear = document.querySelector('.year-filter .range-min').value;
            const maxYear = document.querySelector('.year-filter .range-max').value;
            const minPrice = document.querySelector('.price-filter .range-min').value;
            const maxPrice = document.querySelector('.price-filter .range-max').value;

            // Check if any filters are applied
            const minKmNotEqual = parseFloat(minKm) !== <?= $minKm ?>;
            const maxKmNotEqual = parseFloat(maxKm) !== <?= $maxKm ?>;
            const minYearNotEqual = parseInt(minYear) !== <?= $minYear ?>;
            const maxYearNotEqual = parseInt(maxYear) !== <?= $maxYear ?>;
            const minPriceNotEqual = parseFloat(minPrice) !== <?= $minPrice ?>;
            const maxPriceNotEqual = parseFloat(maxPrice) !== <?= $maxPrice ?>;

            // Filter vehicles based on criteria
            const filteredData = [];

            data.forEach(vehicle => {
                const vehicleKm = parseFloat(vehicle.km);
                const vehicleYear = parseInt(vehicle.year);
                const vehiclePrice = parseFloat(vehicle.price);

                if (vehicleKm >= minKm && vehicleKm <= maxKm && vehicleYear >= minYear && vehicleYear <= maxYear && vehiclePrice >= minPrice && vehiclePrice <= maxPrice) {
                    filteredData.push(vehicle);
                }
            });

            // Create or update filter tags only if filters are applied
            const filterTags = document.getElementById('filter-tags');
            filterTags.innerHTML = ''; // Clear existing tags

            if (minKmNotEqual || maxKmNotEqual) {
                filterTags.innerHTML += `<div><span>Kilometers: ${minKm || 'Any'} - ${maxKm || 'Any'}</span><a href="tagKm">X</a></div>`;
            }

            if (minYearNotEqual || maxYearNotEqual) {
                filterTags.innerHTML += `<div><span>Year: ${minYear || 'Any'} - ${maxYear || 'Any'}</span><a href="tagYear">X</a></div>`;
            }

            if (minPriceNotEqual || maxPriceNotEqual) {
                filterTags.innerHTML += `<div><span>Price: ${minPrice || 'Any'} - ${maxPrice || 'Any'}</span><a href="tagPrice">X</a></div>`;
            }

            assignDeleteFilterAction()
            populateCatalogue(filteredData);
        }

        function assignDeleteFilterAction() {
            const filterTags = document.getElementById('filter-tags');
            const deleteLinks = filterTags.querySelectorAll('a');
            deleteLinks.forEach((link) => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const tagId = this.getAttribute('href');

                    // Handle the deletion of the filter tag based on tagId
                    if (tagId === 'tagKm') {
                        // Update the range slider input values
                        const inputMin = document.querySelector('.km-filter .range-min')
                        const inputMax = document.querySelector('.km-filter .range-max')
                        inputMin.value = inputMin.min;
                        inputMax.value = inputMax.max;
                    } else if (tagId === 'tagYear') {
                        const inputMin = document.querySelector('.year-filter .range-min')
                        const inputMax = document.querySelector('.year-filter .range-max')
                        inputMin.value = inputMin.min;
                        inputMax.value = inputMax.max;
                    } else if (tagId === 'tagPrice') {

                        const inputMin = document.querySelector('.price-filter .range-min')
                        const inputMax = document.querySelector('.price-filter .range-max')
                        inputMin.value = inputMin.min;
                        inputMax.value = inputMax.max;
                    }

                    filterVehicles(data);
                });
            });
        }


        async function fetchDatabase() {
            const phpScriptURL = './admin/functions/db_query.php?action=fetchData';

            try {
                const response = await fetch(phpScriptURL, {
                    method: 'GET', // Use GET method
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                return response.json();
            } catch (error) {
                console.error('Fetch error:', error);
                throw error; // Re-throw the error to propagate it further
            }
        }

        async function fetchDataAndRenderList() {
            try {
                data = await fetchDatabase();
                filterVehicles(data)

            } catch (error) {
                // Handle errors if needed
            }
        }

        function populateCatalogue(data) {
            let pageData
            const itemsPerPage = 12

            // Calculate pagination offsets
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Slice the data based on pagination offsets
            pageData = data.slice(startIndex, endIndex);

            // Calculate total pages based on filtered data
            totalPages = Math.ceil(data.length / itemsPerPage);

            // Update pagination controls (e.g., disable "Previous" on the first page)
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;

            // Update the current page number display
            const currentPageDisplay = document.getElementById('currentPage');
            currentPageDisplay.textContent = `Page ${currentPage}`;

            // Update the total pages display
            const totalPagesDisplay = document.getElementById('totalPages');
            totalPagesDisplay.textContent = `of ${totalPages}`;

            const catalogueContainer = document.querySelector('.catalogue-cars-container')
            catalogueContainer.innerHTML = ""

            if (pageData.length > 0) {
                pageData.forEach(vehicle => {
                    const vehicleContainer = document.createElement('div')
                    vehicleContainer.classList.add('catalogue-car-container')
                    vehicleContainer.innerHTML = `
                <div class="catalogue-car-email">
                            <a class="action-btn" href="?vehicle=${vehicle['brand']}-${vehicle['model']}-${vehicle['year']}">
                                <img src="./admin/assets/src/messages.svg" class="message-icon"></i>
                            </a>
                        </div>
                        <img class="main-car-img" src="${vehicle['img'].replace('../', './')}">
                        <div class="catalogue-car-info-container">
                        <div class="catalogue-car-infos"><span class="bold uppercase">${vehicle['brand']} ${vehicle['model']}</span> - ${vehicle['km']} km, ${vehicle['year']}, ${vehicle['fuel']},<br>
                        ${vehicle['price']} EUR</div>
                    </div>
                        <a href="./vehicle.php?vehicle=${vehicle['id']}">
                            <div class="car-container-overlay"><i class="fa-solid fa-eye"></i></div>
                        </a>
                `
                    catalogueContainer.appendChild(vehicleContainer)
                })

                const actionBtns = document.querySelectorAll('.action-btn');
                const popup = document.getElementById('popup')

                actionBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault()
                        popup.innerHTML = `<div class="popup-div"><div class="form-close-btn">X</div><?php include './components/contact-form.php' ?></div>`
                        messageEvent()
                        // Use setTimeout to ensure that the element is available for modification
                        setTimeout(function() {
                            const subject = document.getElementById('subject');
                            subject.value = "Enquiry for" + btn.getAttribute("href").replace("?vehicle=", " ");
                            subject.readOnly = true

                            const closeBtn = document.querySelector('.form-close-btn')
                            closeBtn.addEventListener('click', (e) => {
                                popup.innerHTML = ``
                            })
                        }, 0);
                    })
                })

            } else {
                catalogueContainer.innerHTML = `<h2>Aucun vehicule correspond aux filtres appliqués</h2>`
            }

        }
    </script>

</body>

</html>