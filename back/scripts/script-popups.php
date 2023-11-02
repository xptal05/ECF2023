<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="popup">
        <div id="popup-header"></div>
        <div id="popup-content"></div>
    </div>

    <?php
    function generateTemplate()
    {
        // Create your template with JavaScript variables
        $template = <<<HTML
        <p>Some content here.</p>
        <p>Dynamic value: <span id="dynamic-value"></span></p>
    HTML;

        return $template;
    }
    ?>

    <script>
        
function createPopup(header, content, formActions) {
    // Create and populate the popup
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv";

    popupDiv.innerHTML += `
        <div class="popup-header">
            <h2>${header}</h2>
            <button class="close-sign close-btn" id="close">X</button>
        </div>
        ${content}
    `;

    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    isPopupOpen = true;

    // Add event listeners after appending the form to the DOM
    formActions();
}

function confirmationPopup(selectedItem) {
    const header = "Archiver l'utilisateur";
    const content = `
        <div class="popup-body-normal">
            <h3>Êtes-vous sûr de vouloir archiver cet utilisateur ${selectedItem.id_user}?</h3>
            <form id="archivForm" class="popup-btns">
                <button class="btn error-btn close-btn" type="reset">NON</button>
                <button class="btn success-btn" type="submit">OUI</button>
            </form>
        </div>
    `;

    function eventListeners() {
        const archivForm = document.getElementById('archivForm');
        archivForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // onSubmit(); // Uncomment this if you have an onSubmit function
        });

        archivForm.addEventListener('reset', (e) => {
            e.preventDefault();
            isPopupOpen = false;
            popupDiv.innerHTML = '';
        });
    }

    createPopup(header, content, eventListeners);
}


        function deletePopup(selectedItem, tableId, name, idKey) {
            console.log(name);

            let tableDb;
            let id = selectedItem[idKey];

            if (name !== "") {
                if (metaTables.includes(dropdownKeys[tableId])) {
                    tableDb = "properties_meta";
                } else {
                    tableDb = dropdownKeys[tableId];
                }
            }

            if (idKey === "id_info") {
                tableDb = "web_page_info";
            }

            const header = `Suppression d'élément des ${tableId}`;
            const content = `
    <div class="popup-body-normal">

        <div>Êtes-vous sûr de vouloir supprimer l'élément <b>${selectedItem[name]}</b> ?</div>
        <form id="archivForm" class="popup-btns">
            <button class="btn error-btn close-btn" type="reset">NON</button>
            <button class="btn success-btn" type="submit">OUI</button>
        </form>
        </div>
    `;

            createPopup(header, content, () => {
                if (selectedItem['type'] == "1") {
                    deteleService(selectedItem); // FOR SERVICES
                } else {
                    arraydelete(tableDb, idKey, id); // FOR ALL OTHER ITEMS
                }
            });

            closePopup();
        }

        const selectedItem = {
            'id_user': '2'
        }

        confirmationPopup(selectedItem)
    </script>

</body>

</html>