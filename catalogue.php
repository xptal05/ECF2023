<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
    <style>

    </style>
</head>

<body>
    <?php include_once "./components/header.php";
    include_once "./func.php";
    $minMaxValues = fetch_min_max_values_filters();

    $minKm = floor($minMaxValues['MIN(km)'] / 100) * 100;
    $maxKm = ceil($minMaxValues['MAX(km)'] / 1000) * 1000;
    $minYear = $minMaxValues['MIN(year)'];
    $maxYear = $minMaxValues['MAX(year)'];
    $minPrice = floor($minMaxValues['MIN(price)'] / 100) * 100;
    $maxPrice = ceil($minMaxValues['MAX(price)'] / 1000) * 1000;

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
                <div class="filter-container-filters">
                    <div class="price-filter">
                        <div class="slider">

                            <div class="range-slider">
                                <p>Price Range</p>
                                <span class="rangeValues"></span>
                                <input class="range-min" value="<?php echo $minPrice; ?>" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" step="500" type="range">
                                <input class="range-max" value="<?php echo $maxPrice; ?>" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" step="500" type="range">
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
                                <input class="range-min" value="<?php echo $minKm; ?>" min="<?php echo $minKm; ?>" max="<?php echo $maxKm; ?>" step="500" type="range">
                                <input class="range-max" value="<?php echo $maxKm; ?>" min="<?php echo $minKm; ?>" max="<?php echo $maxKm; ?>" step="500" type="range">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="catalogue-cars-container">

        </div>
        <div class="table-pagination">
            <button id="prevPage">Previous</button>
            <span id="currentPage">Page 1</span>
            <span id="totalPages">of 1</span>
            <button id="nextPage">Next</button>
        </div>

    </div>
    <?php include_once "./components/footer.php";

    ?>
    <script src="script.js"></script>
    <script src="script-contact.js"></script>
    <script>
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

        function getVals() {
            // Get slider values
            let parent = this.parentNode;
            let slides = parent.getElementsByTagName("input");
            let slide1 = parseFloat(slides[0].value);
            let slide2 = parseFloat(slides[1].value);
            // Neither slider will clip the other, so make sure we determine which is larger
            if (slide1 > slide2) {
                let tmp = slide2;
                slide2 = slide1;
                slide1 = tmp;
            }

            // Determine which slider is being updated
            let rangeType = parent.querySelector("p").textContent;

            // Define symbols for different range types
            const symbols = {
                "Price Range": "EUR",
                "Year Range": "",
                "Km Range": "km"
            };

            // Set the appropriate symbol based on the range type
            let symbol = symbols[rangeType];

            // Display the range values with the symbol
            let displayElement = parent.getElementsByClassName("rangeValues")[0];
            displayElement.innerHTML = `${slide1} ${symbol} - ${slide2} ${symbol}`;
        }

        window.onload = function() {
            // Initialize Sliders
            let sliderSections = document.getElementsByClassName("range-slider");
            for (let x = 0; x < sliderSections.length; x++) {
                let sliders = sliderSections[x].getElementsByTagName("input");
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
                    const kmTag = document.createElement('span');
                    kmTag.textContent = `Kilometers: ${minKm || 'Any'} - ${maxKm || 'Any'}`;
                    filterTags.appendChild(kmTag);
                }

                if (minYearNotEqual || maxYearNotEqual) {
                    const yearTag = document.createElement('span');
                    yearTag.textContent = `Year: ${minYear || 'Any'} - ${maxYear || 'Any'}`;
                    filterTags.appendChild(yearTag);
                }

                if (minPriceNotEqual || maxPriceNotEqual) {
                    const priceTag = document.createElement('span');
                    priceTag.textContent = `Price: ${minPrice || 'Any'} - ${maxPrice || 'Any'}`;
                    filterTags.appendChild(priceTag);
                }


            populateCatalogue(filteredData);
        }


        async function fetchDatabase() {
            const phpScriptURL = './back/db_query.php?action=fetchData';

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

        // Example of how to use fetchUserDatabase and updateList
        async function fetchDataAndRenderList() {
            try {
                data = await fetchDatabase();
                console.log(data)
                filterVehicles(data)

            } catch (error) {
                // Handle errors if needed
            }
        }

        function populateCatalogue(data) {
            console.log('data', data)

            let pageData

            const itemsPerPage = 12
            // Calculate pagination offsets
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            // Slice the data based on pagination offsets
            pageData = data.slice(startIndex, endIndex);
            console.log(pageData)

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

            pageData.forEach(vehicle => {
                const vehicleContainer = document.createElement('div')
                vehicleContainer.classList.add('catalogue-car-container')
                vehicleContainer.innerHTML = `
                <div class="catalogue-car-email">
                            <a class="action-btn" href="?vehicle=${vehicle['brand']}-${vehicle['model']}-${vehicle['year']}">
                                <i class="fa-solid fa-at"></i>
                            </a>
                        </div>
                        <img class="main-car-img" src="${vehicle['img'].replace('../', './')}">
                        <div class="catalogue-car-info-container">
                        <div class="catalogue-car-infos"><span class="bold uppercase">${vehicle['brand']} ${vehicle['model']}</span> - ${vehicle['km']} km, ${vehicle['year']}, ${vehicle['fuel']},<br>'
                        ${vehicle['price']} EUR</div>
                    </div>
                    <div class="car-container-overlay">
                        <a href="./vehicle.php?vehicle=${vehicle['id']}"><i class="fa-solid fa-eye"></i></a>
                    </div>
                `
                catalogueContainer.appendChild(vehicleContainer)
            })

            const actionBtns = document.querySelectorAll('.action-btn');
            const popup = document.getElementById('popup')

            actionBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault()
                    popup.innerHTML = `<div class="popup-div"><div class="form-close-btn">X</div><?php include 'contact-form.php' ?></div>`
                    messageEvent()
                    // Use setTimeout to ensure that the element is available for modification
                    setTimeout(function() {
                        const subject = document.getElementById('subject');
                        subject.value = "Enquiry for" + btn.getAttribute("href").replace("?vehicle=", " ");
                        subject.readOnly = true
                        console.log(subject);

                        const closeBtn = document.querySelector('.form-close-btn')
                        closeBtn.addEventListener('click', (e) => {
                            popup.innerHTML = ``
                        })
                    }, 0);
                })

            })

        }
    </script>

</body>

</html>