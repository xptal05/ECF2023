/* PUSHES DATA INTO THE PHP FUNCTION - AJAX


LIST: DROPDOWN PUSH + DROPDOWN DELETE

MANQUE: IMG, CONFIRMATION, USER, etc...*/

// Reusable function for making AJAX requests

function formvalidation(formType) {
    if (formType == 'userForm') {
        const emailInput = document.getElementById('EmailInput');
        const passwordInput = document.getElementById('PasswordInput');
        console.log(passwordInput)

        // Additional validation for email format
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (!emailPattern.test(emailInput.value)) {
            alert('Please enter a valid email address');
            emailInput.focus();
            return false;
        }

        if (passwordInput.value != "") {
            if (passwordInput.value.length < 5) {
                alert('Password must be at least 5 characters');
                passwordInput.focus();
                return false;
            }

            // Ensure the password contains both letters and numbers
            const containsLetters = /[a-zA-Z]/.test(passwordInput.value);
            const containsNumbers = /\d/.test(passwordInput.value);

            if (!containsLetters || !containsNumbers) {
                alert('Password must contain both letters and numbers');
                passwordInput.focus();
                return false;
            }
        }
        return true
    } else if (formType == 'dropdownForm') {
        return true
    } else if (formType == "webInfoForm") {
        return true
    } else if (formType == "feedback") {
        return true
    } else if (formType == "vehicleForm") {
        return true
    }
    return true
}
function sendAjaxRequest(url, method, data) {
    return fetch(url, {
        method: method,
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            notificationsServeur(data);
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
            return error;
        });
}

function arraydelete(tableDb, idKey, id) {
    const phpScriptURL = './func-one.php?action=deleteData';
    const postData = {
        action: 'delete',
        table: tableDb,
        idKey: idKey,
        id: id
    }
    console.log(postData)

    // Send an AJAX request to update the database
    return sendAjaxRequest(phpScriptURL, 'POST', postData);
}

function deteleService(selectedItem){
    const phpScriptURL = './func-one.php?action=deleteService';
    const postData = {
        action: 'deleteService',
        table: 'web_page_info',
        idKey: 'id_info',
        items: selectedItem
    }
    console.log(postData)

    // Send an AJAX request to update the database
    return sendAjaxRequest(phpScriptURL, 'POST', postData);
}


function vehicleInfoPush(formData) {
    const phpScriptURL = 'func-one.php?action=updateVehicle';
    formData.action = 'updateVehicle'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}

function pushWebPageInfo(formData) {
    console.log('pushweb trigerred',formData); // Form data with inputId and dataValue for select options

    const phpScriptURL = './func-one.php?action=modifyWeb';
    formData.action = 'modifyWeb'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}

function pushServiceInfo(formData) {
    const phpScriptURL = './func-one.php?action=modifyServices';
    formData.action = 'modifyServices'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}

function pushMessageFeedback(formData) {
    const phpScriptURL = './func-one.php?action=modifyMessageFeedback';
    formData.action = 'modifyMessageFeedback'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}


//ACTIONS SPECIFIQUE

function dropdownpush(tableId, name, selectedItem, idKey, additionalValue) {
    let tableDb
    if (metaTables.some(table => table.toLowerCase().trim() == tableId.toLowerCase().trim())) {
        { tableDb = "properties_meta" }
    } else { tableDb = dropdownKeys[tableId] }

    const itemData = {};

    if (dropdownMapping[tableId].array[0].hasOwnProperty("description")) {
        for (const header of formHeadersDropdowns) {
            const inputValue = document.getElementById(`${header}Input`).value;
            itemData[header] = inputValue;
        }
    } else if(tableId == "Couleur"){
        console.log('push dropdown colour')
        console.log('addition', additionalValue)
        const inputValue = document.getElementById(`itemNameInput`).value;
        itemData.itemName = inputValue + '/' + additionalValue
    }
    else {
        const inputValue = document.getElementById(`itemNameInput`).value;
        itemData.itemName = inputValue
    }

    if (additionalValue != undefined && tableId != "Couleur") {
        itemData.brandSelect = additionalValue
    }

    itemData.metaName = (tableId === "Carrosserie") ? "Caroserie" : tableId
    itemData.name = name
    itemData.table = tableDb
    itemData.idKey = idKey

    if (selectedItem) {
        itemData.id = selectedItem[idKey]
    }

    console.log('item id : ',itemData.id)
    console.log('item name : ', itemData.name )

    itemData.action = 'updateDropdown'
    // Send an AJAX request to update the database
    fetch('./func-one.php?action=updateDropdown', {
        method: 'POST',
        body: JSON.stringify(itemData), // Send data as JSON
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            notificationsServeur(data)

            return (data)
            // Handle the response from the server
        })
        .catch(error => {
            console.error('Error:', error);
        });

}


function pushNewFeedback(formData) {

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
            notificationsServeur(data)
            form.classList.toggle('on')
            console.log(data)
        })

        .catch((error) => {
            console.error("Error:", error);
            console.log(data)

        });



}

//users
function arraypush(itemId) {
    const userForm = document.getElementById('userForm');

    // Collect input values
    const userData = {};
    for (const header of formHeaders) {
        const inputValue = document.getElementById(`${header}Input`).value;
        userData[header] = inputValue;
    }
    console.log(userData)

    userData.id = itemId
    userData.action ='updateUser'

    // Send an AJAX request to update the database
    fetch('./func-one.php?action=updateUser', {
        method: 'POST',
        body: JSON.stringify(userData), // Send data as JSON
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            console.log(data);
            popup.innerHTML = ''; // Close the popup window
            fetchDataAndRenderList()
        })
        .catch(error => {
            console.error('Error:', error);
        });

}

//user delete
function arraydeleteUser(itemId) {
    const phpScriptURL = './func-one.php?action=deleteData';
    const postData = {
        key1: 'value1',
        key2: 'value2',
        action: 'delete',
        table: 'users',
        idKey : 'id_user',
        id: itemId
    }

    // Send an AJAX request to update the database
    fetch(phpScriptURL, {
        method: 'POST',
        body: JSON.stringify(postData), // Send data as JSON
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            console.log(data);
            fetchDataAndRenderList()
            popup.innerHTML = '';
        })
        .catch(error => {
            console.error('Error:', error);
        });

}