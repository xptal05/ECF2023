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
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" type="text/css" href="./style/style-svg-btn.css">
    <style>
        <?php $imgBackground = $_GET['id'] ? $vehicle_img : 'src/img.png';
        echo ".img-container:before {background-image: url('$imgBackground');}"; ?>.custom-select {
            position: relative;
            display: inline-block;
        }

        .select-options {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .option {
            padding: 5px;
            cursor: pointer;
        }

        .option:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body class="application-window">
    <section id="notifications"></section>
    <section class="nav">
        <?php include_once "./components/menu.php";
        ?>
    </section>
    <section id="popup"></section>
    <section class="application">
        <h1><?php

            $vehicle_infos = [];

            if (isset($_GET['id'])) {
                $vehicle_infos = vehicle_infos();
            }

            function vehicle_infos()
            {
                global $pdo;
                $vehicleID = $_GET['id'];
                $sql = 'SELECT
                brands.name AS brandname,
                models.name AS modelname,
                statuses.name AS statusname,
                vehicles.*,
                images.link AS img,
                images.id_img AS id_img,
                IFNULL((SELECT properties_meta.value FROM properties_meta
                         LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
                         WHERE properties_meta.name = "Carbourant"
                           AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Carbourant,
                IFNULL((SELECT properties_meta.value FROM properties_meta
                         LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
                         WHERE properties_meta.name = "Caroserie"
                           AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Caroserie,
                IFNULL((SELECT properties_meta.value FROM properties_meta
                         LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
                         WHERE properties_meta.name = "Transmission"
                           AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Transmission,
                IFNULL((SELECT properties_meta.value FROM properties_meta
                         LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
                         WHERE properties_meta.name = "Couleur"
                           AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Couleur,
                IFNULL((SELECT properties_meta.value FROM properties_meta
                         LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
                         WHERE properties_meta.name = "Portes"
                           AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Portes,
                IFNULL((SELECT vehicle_properties.property FROM vehicle_properties
                         WHERE vehicle_properties.property_name = "Options" AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Options,
                IFNULL((SELECT GROUP_CONCAT(
                                DISTINCT images.link
                                ORDER BY images.link
                                SEPARATOR ", "
                            ) FROM images 
                        WHERE images.associated_to_vehicle = vehicles.id_vehicle AND images.type = 1), "") AS gallery,
                IFNULL((SELECT GROUP_CONCAT(
                                DISTINCT images.id_img
                                ORDER BY images.id_img
                                SEPARATOR ", "
                            ) FROM images 
                        WHERE images.associated_to_vehicle = vehicles.id_vehicle AND images.type = 1), "") AS gallery_ids       
                FROM vehicles
                LEFT JOIN brands ON vehicles.brand = brands.id_brand
                LEFT JOIN models ON vehicles.model = models.id_model
                LEFT JOIN statuses ON vehicles.status = statuses.id_status
                LEFT JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
                WHERE vehicles.id_vehicle = ?';

                $statement = $pdo->prepare($sql);
                $statement->bindParam(1, $vehicleID, PDO::PARAM_INT);
                if ($statement->execute()) {
                    $vehicle_info = $statement->fetch(PDO::FETCH_ASSOC);
                    return $vehicle_info;
                } else {
                    echo 'Error executing query';
                }
            }

            foreach ($vehicle_infos as $key => $value) {
                ${'vehicle_' . $key} = !empty($value) ? $value : '';
            }

            $vehicle_img = !empty($vehicle_img) ? $vehicle_img . '" data-value="' . $vehicle_id_img : '../images_voiture/img.png';
            $imageGalleryImgs = !empty($vehicle_infos['gallery']) ? explode(", ", $vehicle_infos['gallery']) : '';

            $header = $_GET['id'] ? 'Modifier Vehicle' : 'New Vehicle';
            echo $header
            ?></h1>
        <form id="vehicleForm" class="application-body vehicle-page" action="" method="post">
            <div class="vehicle-details application-body span-12">
                <div class="img-container span-4 container">
                    <div class="img-foreground">
                        <a href="?modify=img" class="actionbtn mainCarImage"><img class="svg" src="./src/edit_black.svg"></a>
                    </div>
                    <img class="car-tumbnail" src="<?php echo $vehicle_img ?>" style="width:100%" name="mainImg">
                </div>
                <div class="main-info container span-8">
                    <div class="custom-select">
                        <label>Marque <a href="?add=Marque" class="actionbtn svg-btn" title="Ajouter une marque">+</a></label>
                        <input type="text" id="MarqueInput" class="aa" required value="<?php echo $vehicle_brandname; ?>" autocomplete="off">
                        <div class="select-options" id="MarqueOptions">
                        </div>
                    </div>
                    <div class="custom-select">
                        <label>Modèle <a href="?add=Modèle" class="actionbtn svg-btn" title="Ajouter un modèle">+</a></label>
                        <input type="text" id="ModèleInput" class="aa" required value="<?php echo $vehicle_modelname; ?>" autocomplete="off">
                        <div class="select-options" id="ModèleOptions">
                        </div>
                    </div>
                    <div>
                        <label for="Price">Prix</label>
                        <input type="number" name="Price" id="Price" value="<?php echo $vehicle_price ?>" autocomplete="off" required>
                    </div>

                    <div>
                        <label for="year">Année</label>
                        <input type="number" name="year" id="year" value="<?php echo $vehicle_year ?>" autocomplete="off" required>
                    </div>

                    <div>
                        <label for="km">Kilométres</label>
                        <input type="number" name="km" id="km" value="<?php echo $vehicle_km ?>" autocomplete="off" required>
                    </div>

                    <div>
                        <label for="Conformity">Conformité</label>
                        <input type="text" name="Conformity" id="Conformity" value="<?php echo $vehicle_conformity ?>">
                    </div>
                    <div>
                        <label for="Consumption">Consommation</label>
                        <input type="text" name="Consumption" id="Consumption" value="<?php echo $vehicle_consumption ?>">
                    </div>
                    <div class="custom-select">
                        <label>Statut</label>
                        <input type="text" id="StatutInput" class="aa" required value="<?php echo $vehicle_statusname; ?>" autocomplete="off">
                        <div class="select-options" id="StatutOptions">
                        </div>
                    </div>
                </div>
            </div>
            <div class="info-property span-6">
                <h2>Informations principales</h2>
                <?php
                $propertyValues = [
                    'Carbourant' => $vehicle_infos['Carbourant'],
                    'Carrosserie' => $vehicle_infos['Caroserie'],
                    'Transmission' => $vehicle_infos['Transmission'],
                    'Couleur' => $vehicle_infos['Couleur'],
                    'Portes' => $vehicle_infos['Portes'],
                ];

                $propertiesMain = ['Carrosserie', 'Transmission', 'Carbourant', 'Portes', 'Couleur'];
                $propertiesOther = ['Climatisation Manuelle', 'Climatisation Automatique', 'Régulateur Vitesse', 'Capteurs de stationnement', 'Caméra de recul', 'Connectivité Smartphone', 'Sieges chauffants', 'Détecteur de pluie', 'ABS', 'Navigation GPS'];
                ?>
                <div class="container">
                    <?php
                    foreach ($propertiesMain as $property) {
                        echo '<div class="select-options-container">';
                        echo '<label for="' . $property . '">' . $property . ' </label>';
                        echo '<select id="' . $property . '" name="' . $property . '" >';
                        echo '<option value="">Loading options...</option>';
                        echo '</select>';
                        echo '<a href="?add=' . $property . '" class="actionbtn svg-btn" title="Ajouter">+</a>';
                        echo '</div>';
                    }
                    ?>
                </div>



                <div class="gallery container">
                    <a href="?modify=img" class="actionbtn CarGalleryImages"><img class="" src="./src/edit_black.svg"></a>
                    <?php
                    if ($imageGalleryImgs != '') {
                        $galleryIds = explode(', ', $vehicle_infos['gallery_ids']);
                        $galleryLinks = $vehicle_infos['gallery'];

                        // Iterate through both arrays simultaneously
                        foreach ($imageGalleryImgs as $index => $imageUrl) {
                            $imageId = isset($galleryIds[$index]) ? $galleryIds[$index] : '';
                            echo '<img src="' . $imageUrl . '" alt="Car Image" class="carGallerythumbnail" data-value="' . $imageId . '">';
                        }
                    } else {
                        echo 'Car image gallery';
                    }
                    ?>
                    </img>
                </div>

            </div>
            <div class="info-options span-6">
                <h2>Equipements et Options</h2>
                <div class="container grid-4-checkbox">
                    <?php
                    foreach ($propertiesOther as $property) {

                        $isChecked = (strpos($vehicle_infos['Options'], $property) !== false) ? 'checked' : '';

                        echo '<label for="' . $property . '">' . $property . '</label>
                        <input type="checkbox" id="' . $property . '" name="' . $property . '" value="Oui" ' . $isChecked . '>';
                    }
                    ?>
                    <div class="autres-info-container span-4">
                        <label>Autres Informations</label>
                        <textarea rows="5" name="other"><?php echo $vehicle_infos['other_equipment']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="button-container span-2-end">
                <input type="reset" class="btn error-btn" value="Annuler">
                <input type="submit" class="btn success-btn" value="Sauvegarder">
            </div>
        </form>
    </section>
    <script src="script-dropdowns.js"></script>
    <script src="./scripts/script-notifications.js"></script>
    <script src="./scripts/script-popups.js"></script>
    <script src="./scripts/script-postDb.js"></script>
    <script>
        const notifications = document.getElementById('notifications')
        let isPopupOpen = false
        const metaTables = [
            "carrosserie",
            "carbourant",
            "couleur",
            "portes",
            "transmission"
        ];
        const formHeadersDropdowns = ["itemName", "itemDescription"];

        const formInputs = document.querySelectorAll('#vehicleForm input[type="text"].aa');

        fetchAndUpdateDropdownData()

        function optionLoop() { //DROPDOWNS
            const selectOptions = document.querySelectorAll('select');
            selectOptions.forEach(select => {
                const selectId = select.id;
                const dropdownArray = dropdownMapping[selectId];

                if (dropdownArray) {
                    select.innerHTML = ""
                    var innerArray = dropdownArray.array;
                    var idKey = dropdownArray.idKey;
                    var name = dropdownArray.name;

                    innerArray.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item[idKey]; // Set the value based on idKey
                        option.innerText = item[name]; // Set the text based on name
                        select.appendChild(option); // Append the option to the specific select element
                    });
                }

                //SELECTED OPTION
                var selectedValue = <?php echo json_encode($propertyValues); ?>;

                // Loop through the options in the select element
                for (var i = 0; i < select.options.length; i++) {
                    var option = select.options[i];

                    // Check if the option's value matches the selected value
                    if (option.innerText === selectedValue[selectId]) {
                        // Set the 'selected' attribute to true for the matching option
                        option.selected = true;
                    }
                }

            });
        }

        function hiddenOptions() { //HIDDEN DROPDOWNS BRAND + MODEL
            formInputs.forEach(input => {
                const inputId = input.id.replace("Input", "");

                if (dropdownMapping[inputId]) {
                    const mapping = dropdownMapping[inputId];
                    createOptions(inputId, mapping);
                }

                input.addEventListener("click", function() {
                    const selectOptions = this.nextElementSibling;
                    if (selectOptions) {
                        selectOptions.style.display = selectOptions.style.display === "block" ? "none" : "block";
                    }
                });

                input.addEventListener("keydown", function() {
                    const selectOptions = this.nextElementSibling;
                    const options = selectOptions.querySelectorAll(".option");
                    const searchValue = input.value.toLowerCase();
                    options.forEach(option => {
                        const optionText = option.textContent.toLowerCase();
                        option.style.display = optionText.includes(searchValue) ? "block" : "none";
                    });
                });

                input.addEventListener("blur", function(e) {
                    const selectOptions = this.nextElementSibling;
                    const inputOptions = selectOptions.querySelectorAll(".option");

                    // Check if the blur event target is not the selectOptions.
                    setTimeout(function() {
                        if (!e.relatedTarget || !e.relatedTarget.classList.contains("option")) {
                            if (selectOptions.style.display === "block") {
                                selectOptions.style.display = "none";
                            }
                        }
                    }, 100);
                    if (input.value !== "") {
                        const selectedOption = Array.from(inputOptions).find(option => option.textContent.toLowerCase() === input.value.toLowerCase());
                        if (!selectedOption) {
                            // Reset the input value to an empty string.
                            input.value = "";
                            alert('You need to select a value from the dropdown');
                        }
                    }
                });

                selectOption(inputId);
            });
        }


        // Function to create options based on the mapping
        function createOptions(inputId, mapping) {
            const selectOptions = document.getElementById(`${inputId}Options`);
            selectOptions.innerHTML = ""
                optionsArray = mapping.array;

            optionsArray.forEach(option => {
                const optionDiv = document.createElement("div");
                optionDiv.className = "option";
                optionDiv.dataset.value = option[mapping.idKey];
                optionDiv.textContent = option[mapping.name];
                selectOptions.appendChild(optionDiv);
            });
        }


        function selectOption(inputId) {
            // Your code for selectOption function

            const selectOptions = document.getElementById(`${inputId}Options`);
            const inputOptions = selectOptions.querySelectorAll(".option");

            inputOptions.forEach(option => {
                option.addEventListener("click", function() {
                    const value = this.getAttribute("data-value");
                    const input = document.getElementById(`${inputId}Input`);
                    input.value = this.textContent;
                    selectOptions.style.display = "none";
                });
            });
        }


        //FORM SUBMIT
        const selectedOptions = {}; // Object to store selected options
        const form = document.getElementById("vehicleForm");

        form.addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the form from submitting for demonstration purposes
            const urlParams = new URLSearchParams(window.location.search);
            const formData = {}; // For form data
            formData.id = urlParams.get('id')


            // Iterate through the selected input elements (dropdowns)
            formInputs.forEach(input => {
                const inputId = input.id.replace("Input", "");
                const selectOptions = document.getElementById(`${inputId}Options`);

                // Find the selected option with a data-value attribute
                const selectedOption = Array.from(selectOptions.querySelectorAll(".option")).find(option => option.textContent === input.value);

                if (selectedOption) {
                    const dataValue = selectedOption.getAttribute("data-value");
                    formData[inputId] = dataValue;
                }
            });

            // Iterate through all input and select elements
            // Initialize an empty array to store checked options
            const checkedOptions = [];

            // Loop through all input and select elements within the form
            form.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.type === "checkbox") {
                    if (input.checked) {
                        // If the checkbox is checked, add its name to the checkedOptions array
                        checkedOptions.push(input.name);
                    }
                } else {
                    // Handle other input, select, and textarea elements
                    formData[input.name] = input.value;
                }
            });

            form.querySelectorAll('img').forEach(input => {
                formData[input.getAttribute("name")] = input.getAttribute("data-value");
            });

            formData["Options"] = checkedOptions.join(", ");
            console.log(formData)
            vehicleInfoPush(formData)
        })


        // trigger pop modify drop downs
        const actionBtns = document.querySelectorAll('a.actionbtn');
        actionBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('url', e.currentTarget.getAttribute('href'))
                console.log(dropdownMapping)
                const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0];
                const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

                if (tableId == "img") {
                    console.log('image')
                    const gallerytype = e.currentTarget.className;
                    if (actionBtn == "delete") {
                        imgDeletePopup()
                    } else if (actionBtn == "add") {
                        imgAddPopup(gallerytype)
                    } else if (actionBtn == "modify") {
                        imgModifyPopup(gallerytype)
                    }
                } else {

                    const idKey = dropdownMapping[tableId]['idKey'];
                    const name = dropdownMapping[tableId]['name'];
                    console.log('tabel id:', tableId, 'idKey:', idKey, 'name', name)

                    selectedItem = ""
                    dropdownPopup(selectedItem, tableId, name, idKey)

                    //it should refetches the data from db, but reselects the options as well, it needs to be modified
                    //it does not work!!!!!!
                    fetchAndUpdateDropdownData()
                }
            })
        })

        let imageData = {}
        async function fetchAndUpdateImageInfo() {
            try {
                // Fetch dropdown data from the server
                const imageInfoData = await fetchImageInfoFromServer();
                // Group the fetched data by "type" key
                imageData = imageInfoData
                filterGallery();
            } catch (error) {
                console.log('Error fetching web info data:', error);
            }
        }

        // This function will fetch web info data from the server
        async function fetchImageInfoFromServer() {
            const phpScriptURL = './func-one.php?action=fetchData';
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
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Fetch error:', error);
                throw error; // Re-throw the error to propagate it further
            }
        }
    </script>

    <script src="./components/menu.js"></script>
</body>

</html>