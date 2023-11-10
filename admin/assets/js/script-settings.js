//js for settings page
const notifications = document.getElementById('notifications')

fetchAndUpdateDropdownData()    //get the dropdowns

let isPopupOpen = false
const metaTables = [
    "caroserie",
    "carbourant",
    "couleur",
    "portes",
    "transmission"
]
const formHeadersDropdowns = ["itemName", "itemDescription"]

//get the dropdown fields info to page
function dropdowntopage() {

    const dropdownsContainer = document.getElementById('dropdownsContainer')
    dropdownsContainer.innerHTML = ""

    for (const key in dropdownMapping) {
        if (dropdownMapping.hasOwnProperty(key)) {
            if (key === "Droits") {
                // Skip the "Droits" key -> no modification possible for the user rights
                continue;
            }

            const dropdownContainer = document.createElement('div')
            dropdownContainer.classList.add('tbl')
            dropdownContainer.innerHTML = `<div class="table-header"><h3>${key}</h3><a class="actionbtn svg-btn" href="?add=${key}" title="Ajouter" >+</a></div>`

            dropdownsContainer.appendChild(dropdownContainer)
            const { array, idKey, name } = dropdownMapping[key]

            const table = document.createElement('table')
            dropdownContainer.appendChild(table)
            for (const dropdownObj of array) {
                let brandText = ""
                let color = ""

                const optionRow = document.createElement('tr')

                //put the info according to the table type
                if (key == "Modèle") {
                    const brandArray = dropdownMapping['Marque'].array
                    const brandKey = dropdownObj['brand']
                    const brandName = brandArray.find(brandObj => brandObj.id_brand === brandKey).name
                    brandText = `<td>${brandName}</td>`
                    optionRow.innerHTML = `
                    ${brandText}<td>${dropdownObj[name]}</td><td><a class="actionbtn svg-btn" href="?modify=${key}-${Number(dropdownObj[idKey])}" title="Modifier"><img src="./assets/src/edit_black.svg"></a></td>
                    <td><a class="actionbtn svg-btn" href="?delete=${key}-${Number(dropdownObj[idKey])}" title="Supprimer"><img src="./assets/src/box-archive.svg"></a></td>`

                } else if (key == "Couleur") {
                    colorCode = dropdownObj[name].split("/")[1]
                    colorName = dropdownObj[name].split("/")[0]
                    color = `<td><div class="color-point-form"  style="background:${colorCode}"></div></td>`
                    optionRow.innerHTML = `<td>${colorName}</td>${color}<td><a class="actionbtn svg-btn" href="?modify=${key}-${Number(dropdownObj[idKey])}" title="Modifier"><img src="./assets/src/edit_black.svg"></a></td>
                    <td><a class="actionbtn svg-btn" href="?delete=${key}-${Number(dropdownObj[idKey])}" title="Supprimer"><img src="./assets/src/box-archive.svg"></a></td>`

                } else {
                    optionRow.innerHTML = `<td>${dropdownObj[name]}</td><td><a class="actionbtn svg-btn" href="?modify=${key}-${Number(dropdownObj[idKey])}" title="Modifier"><img src="./assets/src/edit_black.svg"></a></td>
                    <td><a class="actionbtn svg-btn" href="?delete=${key}-${Number(dropdownObj[idKey])}" title="Supprimer"><img src="./assets/src/box-archive.svg"></a></td>`
                }
                table.appendChild(optionRow)
            }
        }
    }
// add actions to the buttons generated
    const actionBtns = document.querySelectorAll('a.actionbtn')
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            const itemId = parseInt(e.currentTarget.getAttribute('href').split('-')[1], 10)
            const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0]
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '')

            const idKey = dropdownMapping[tableId]['idKey']
            const name = dropdownMapping[tableId]['name']

            const selectedItem = dropdownMapping[tableId]['array'].find(item => parseInt(item[idKey], 10) === itemId)
            if (selectedItem) {
                if (actionBtn == 'modify') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = '' // Close the popup window
                        isPopupOpen = false
                    }
                    dropdownPopup(selectedItem, tableId, name, idKey)
                } else if (actionBtn == 'delete') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = '' // Close the popup window
                        isPopupOpen = false
                    }
                    deletePopup(selectedItem, tableId, name, idKey)
                }
            } else if (actionBtn == 'add') {
                if (isPopupOpen == true) {
                    popup.innerHTML = '' // Close the popup window
                    isPopupOpen = false
                }
                dropdownPopup(selectedItem, tableId, name, idKey)
            } else { (console.log('item not found')) }
        })
    })
}

function formvalidation() {
    return true
}


