
const dropdownMapping = {
    "Droits": { array: [], idKey: "id_role", name: "name" },
    "Statut": { array: [], idKey: "id_status", name: "name" },
    "Marque": { array: [], idKey: "id_brand", name: "name" },
    "Modèle": { array: [], idKey: "id_model", name: "name" },
    "Carrosserie": { array: [], idKey: "id_meta", name: "value" },
    "Carbourant": { array: [], idKey: "id_meta", name: "value" },
    "Couleur": { array: [], idKey: "id_meta", name: "value" },
    "Portes": { array: [], idKey: "id_meta", name: "value" },
    "Transmission": { array: [], idKey: "id_meta", name: "value" },
};

const dropdownKeys = {
    "Droits": "roles",
    "Statut": "status",
    "Marque": "brand",
    "Modèle": "model",
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
console.log(dropdownMapping)
    
}



function hiddeOptions(){
  // Get references to the input fields
  const marqueInput = document.getElementById('marqueInput');
  const modeleInput = document.getElementById('modeleInput');
  console.log(modeleInput)
  
  // Function to update the filtered data list for an input field
  function updateFilteredList(inputElement, property) {
    inputElement.addEventListener('input', function () {
      const inputValue = inputElement.value.trim().toLowerCase();
      const filteredData = dropdownMapping[property].array.filter(
        item => item[dropdownMapping[property].name].toLowerCase().includes(inputValue)
      );
  
      // Clear any previous list
      const filteredDataList = document.getElementById(property.toLowerCase() + 'FilteredList');
      filteredDataList.innerHTML = '';
  
      // Populate the list with filtered data
      filteredData.forEach(item => {
        const option = document.createElement('option');
        option.value = item[dropdownMapping[property].idKey];
        option.textContent = item[dropdownMapping[property].name];
        filteredDataList.appendChild(option);
      });
    });
  
    // Add a focus event to show the filtered items
    inputElement.addEventListener('focus', function () {
      const filteredDataList = document.getElementById(property.toLowerCase() + 'FilteredList');
      filteredDataList.style.display = 'block';
    });
  
    // Add a blur event to hide the filtered items
    inputElement.addEventListener('blur', function () {
      const filteredDataList = document.getElementById(property.toLowerCase() + 'FilteredList');
      filteredDataList.style.display = 'none';
    });
  }

  // Function to check if the entered value is valid
  function isValueValid(value, property) {
    const lowerCaseValue = value.toLowerCase();
    return dropdownMapping[property].array.some(item => item.name.toLowerCase() === lowerCaseValue);
  }

  marqueInput.addEventListener('blur', () => {
    const value = marqueInput.value;
    const property = "Marque";
    if (!isValueValid(value, property)) {
      // Display a popup or show an error message
      alert("Invalid Marque value!");
      // You can also clear the input field or take other actions as needed
    }
  });
  
  modeleInput.addEventListener('blur', () => {
    const value = modeleInput.value;
    const property = "Modèle";
    if (!isValueValid(value, property)) {
      // Display a popup or show an error message
      alert("Invalid Modèle value!");
      // You can also clear the input field or take other actions as needed
    }
  });
  
  // Call the function for "Marque" and "Modèle" input fields
  updateFilteredList(marqueInput, 'Marque');
  updateFilteredList(modeleInput, "Modèle");
}
  