
const dropdownMapping = {
    "Droits": { array: [], idKey: "id_role", name: "name" },
    "Statut": { array: [], idKey: "id_status", name: "name" },
    "Marque": { array: [], idKey: "id_brand", name: "name" },
    "Modèle": { array: [], idKey: "id_model", name: "name"},
    "Carrosserie": { array: [], idKey: "id_meta", name: "value" },
    "Carbourant": { array: [], idKey: "id_meta", name: "value" },
    "Couleur": { array: [], idKey: "id_meta", name: "value" },
    "Portes": { array: [], idKey: "id_meta", name: "value" },
    "Transmission": { array: [], idKey: "id_meta", name: "value" },
};

const dropdownKeys = {
    "Droits": "user_roles",
    "Statut": "statuses",
    "Marque": "brands",
    "Modèle": "models",
    "Carrosserie": "caroserie",
    "Carbourant": "carbourant",
    "Couleur": "couleur",
    "Portes": "portes",
    "Transmission": "transmission"
};


    // Function to fetch dropdown data and update dropdownMapping
    async function fetchAndUpdateDropdownData() {
        try {
            // Fetch dropdown data from the server
            const dropdownData = await fetchDropdownDataFromServer();

            // Loop through the keys and update dropdownMapping based on the received data
            for (const key in dropdownKeys) {
                const dataKey = dropdownKeys[key];
                if (dropdownData[dataKey] && Array.isArray(dropdownData[dataKey])) {
                    // Use the idKey and name from the dropdownMapping
                    dropdownMapping[key] = { array: dropdownData[dataKey], idKey: dropdownMapping[key].idKey, name: dropdownMapping[key].name };
                } else {
                    console.log(`No data for ${key}`);
                }
            }
            // Now that dropdownMapping is updated, proceed to set up your pop-up
            pageDropdownsActions();
        } catch (error) {
            console.log('Error fetching dropdown data:', error);
        }
    }


    // This function will fetch dropdown data from the server
    async function fetchDropdownDataFromServer() {
        // You can use the Fetch API to request data from your PHP script here
        // This function should return an object with properties like rolesArray, statusArray, etc.
        // For example:
        const phpScriptURL = './func-one.php?action=fetchDropdowns';
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


function pageDropdownsActions(){
    const currentUrl = window.location.pathname.split('/').pop()

    if (currentUrl == "user-settings.php") {return dropdownMapping}
    else if (currentUrl == "settings.php") {dropdowntopage()}
    else if (currentUrl == "upload-img.php") {dropdowntopage()}
    else if (currentUrl == "vehicle-form.php") {
        optionLoop()
        hiddenOptions()
    }
    
}

