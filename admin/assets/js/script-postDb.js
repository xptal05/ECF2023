/* PUSHES DATA INTO THE PHP FUNCTION - AJAX*/

// Reusable function for making AJAX requests

function formvalidation(formType) {
    if (formType == 'userForm') {
        const emailInput = document.getElementById('EmailInput')
        const passwordInput = document.getElementById('PasswordInput')

        // Additional validation for email format
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        if (!emailPattern.test(emailInput.value)) {
            alert('Please enter a valid email address')
            emailInput.focus()
            return false
        }

        if (passwordInput.value != "") {
            if (passwordInput.value.length < 5) {
                alert('Password must be at least 5 characters')
                passwordInput.focus()
                return false
            }

            // Ensure the password contains both letters and numbers
            const containsLetters = /[a-zA-Z]/.test(passwordInput.value)
            const containsNumbers = /\d/.test(passwordInput.value)

            if (!containsLetters || !containsNumbers) {
                alert('Password must contain both letters and numbers')
                passwordInput.focus()
                return false
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
            notificationsServeur(data)
        })
        .catch(error => {
            console.error('Error:', error)
            return error
        })
}

//delete data function
function arraydelete(tableDb, idKey, id, csrf_token) {
    const phpScriptURL = './functions/db_query.php?action=deleteData'
    const postData = {
        action: 'delete',
        table: tableDb,
        idKey: idKey,
        id: id,
        csrf_token : csrf_token
    }

    // Send an AJAX request to update the database
    return sendAjaxRequest(phpScriptURL, 'POST', postData)
}

//delete services from web-page info table
function deteleService(selectedItem, csrf_token) {
    const phpScriptURL = './functions/db_query.php?action=deleteService'
    const postData = {
        action: 'deleteService',
        table: 'web_page_info',
        idKey: 'id_info',
        items: selectedItem,
        csrf_token : csrf_token
    }

    // Send an AJAX request to update the database
    return sendAjaxRequest(phpScriptURL, 'POST', postData)
}

//update vehicle infos
function vehicleInfoPush(formData) {
    const phpScriptURL = 'functions/db_query.php?action=updateVehicle'
    formData.action = 'updateVehicle'

    sendAjaxRequest(phpScriptURL, 'POST', formData)
        .then(() => {
            setTimeout(() => {
                window.location.href = 'vehicles.php';
            }, 4000)
        });
}

//update web page infos (except services)
function pushWebPageInfo(formData) {
    const phpScriptURL = './functions/db_query.php?action=modifyWeb'
    formData.action = 'modifyWeb'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}

function pushServiceInfo(formData) {
    const phpScriptURL = './functions/db_query.php?action=modifyServices'
    formData.action = 'modifyServices'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}

function pushMessageFeedback(formData) {
    const phpScriptURL = './functions/db_query.php?action=modifyMessageFeedback'
    formData.action = 'modifyMessageFeedback'

    return sendAjaxRequest(phpScriptURL, 'POST', formData)
}


//ACTIONS SPECIFIQUE
function dropdownpush(tableId, name, selectedItem, idKey, additionalValue, csrf_token) {
    let tableDb
    //get the table db name according the table ID
    if (metaTables.some(table => table.toLowerCase().trim() == tableId.toLowerCase().trim()) || tableId == "Carrosserie" || tableId == "caroserie") {
        { tableDb = "properties_meta" }
    } else { tableDb = dropdownKeys[tableId] }

    const itemData = {}

    if (dropdownMapping[tableId].array[0].hasOwnProperty("description")) {
        for (const header of formHeadersDropdowns) {
            const inputValue = document.getElementById(`${header}Input`).value
            itemData[header] = inputValue
        }
    } else if (tableId == "Couleur") {
        const inputValue = document.getElementById(`itemNameInput`).value
        itemData.itemName = inputValue + '/' + additionalValue
    }
    else {
        const inputValue = document.getElementById(`itemNameInput`).value
        itemData.itemName = inputValue
    }

    if (additionalValue != undefined && tableId != "Couleur") {
        itemData.brandSelect = additionalValue
    }

    //add info into itemData
    itemData.metaName = (tableId === "Carrosserie") ? "Caroserie" : tableId
    itemData.name = name
    itemData.table = tableDb
    itemData.idKey = idKey
    itemData.csrf_token = csrf_token

    if (selectedItem) {
        itemData.id = selectedItem[idKey]
    }

    itemData.action = 'updateDropdown'
    // Send an AJAX request to update the database
    fetch('./functions/db_query.php?action=updateDropdown', {
        method: 'POST',
        body: JSON.stringify(itemData),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            notificationsServeur(data)
            //if vehicle pages add the value into input field
            if (itemData.metaName == "Caroserie") {
                itemData.metaName = "Carrosserie"
            }
            const input = document.getElementById(itemData.metaName + 'Input')

            if (input) {
                input.value = itemData.itemName
            }
            //delay the set select options so that the data is refetched first
            setTimeout(() => {
                const select = document.getElementById(itemData.metaName)
                if (select) {
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].text == itemData.itemName) {
                            // Set the selected index to the index of the matching option
                            select.options[i].selected = true;
                            break;
                        }
                    }
                }
            }, 100)
            return (data)

        })
        .catch(error => {
            console.error('Error:', error)
        })

}

//New feedback
function pushNewFeedback(formData) {

    const phpScriptURL = '../BACK/functions/db_query.php?action=newFeedback'
    formData.action = 'newFeedback'

    // Send an AJAX request to update the database
    fetch(phpScriptURL, {
        method: 'POST',
        body: JSON.stringify(formData),
        headers: {
            'Content-Type': 'application/json',
        },
    })

        .then((response) => response.json())
        .then((data) => {
            // Show a success message or redirect to a success page
            notificationsServeur(data)
            form.classList.toggle('on')
        })

        .catch((error) => {
            console.error("Error:", error)
        })
}

//users update or insert
function arraypush(itemId) {
    const userForm = document.getElementById('userForm')
    const csrf_token = userForm.querySelector('input[type="hidden"]').value

    // Collect input values
    const userData = {}
    for (const header of formHeaders) {
        const inputValue = document.getElementById(`${header}Input`).value
        userData[header] = inputValue
    }

    userData.id = itemId
    userData.action = 'updateUser'
    userData.csrf_token = csrf_token

    // Send an AJAX request to update the database
    fetch('./functions/db_query.php?action=updateUser', {
        method: 'POST',
        body: JSON.stringify(userData),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            popup.innerHTML = '' // Close the popup window
            fetchDataAndRenderList()
        })
        .catch(error => {
            console.error('Error:', error)
        })

}

//user delete
function arraydeleteUser(itemId, csrf_token) {
    const phpScriptURL = './functions/db_query.php?action=deleteData'
    const postData = {
        action: 'delete',
        table: 'users',
        idKey: 'id_user',
        id: itemId,
        csrf_token: csrf_token
    }

    // Send an AJAX request to update the database
    fetch(phpScriptURL, {
        method: 'POST',
        body: JSON.stringify(postData),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            fetchDataAndRenderList()
            popup.innerHTML = ''
        })
        .catch(error => {
            console.error('Error:', error)
        })

}

//delet image from gallery and DB
function deleteImage(selectedImage, csrf_token, notify = true) {
    const phpScriptURL = './functions/db_query.php?action=deleteImg'
    const postData = {
        action: 'deleteImg',
        id_img: selectedImage.id_img,
        image_link: selectedImage.link,
        csrf_token: csrf_token
    }

    // Send an AJAX request to update the database
    fetch(phpScriptURL, {
        method: 'POST',
        body: JSON.stringify(postData),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Clear the innerHTML and remove the popup window
            const subPopup = document.getElementById('sub-popup')
            subPopup.innerHTML = ''
            subPopup.classList.remove('popup-window')
            //refetch images
            fetchAndUpdateImageInfo()

            //update UI - sidebar
            const imageIdInput = document.querySelector('.image-info-sidebar')
            let imgLink = imageIdInput.querySelector(".gallery-image-selected")
            let imageName = imageIdInput.querySelector("input")
            let actionLinks = imageIdInput.querySelectorAll("a")

            imgLink.src = ""
            imageName.value = ""
            actionLinks[0].href = ""
            actionLinks[1].href = ""

        })
        .catch(error => {
            console.error('Error:', error)
        })
}