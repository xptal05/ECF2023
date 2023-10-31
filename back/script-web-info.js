
const webInfo = {
    'Services': { array: [], key: 1 },
    'Address': { array: [], key: 2 },
    'Contact': { array: [], key: 3 },
    'Hours': { array: [], key: 4 },
    'About': { array: [], key: 5 },
    'Reasons': { array: [], key: 7 }
};

const tables = [
    { name: "Services", id: "servicetbl", headers: ["text", "description.text", "order"] },
    { name: "Hours", id: "hourstbl", headers: ["text", "order"] },
    { name: "Contact", id: "contacttbl", headers: ["text", "category", "order"] },
    { name: "Address", id: "addresstbl", headers: ["TEXT", "CATEGORY", "ORDER"] },
    { name: "About", id: "abouttbl", headers: ["TEXT"] },
    { name: "Reasons", id: "reasonstbl", headers: ["TEXT", "CATEGORY", "ORDER"] },
];

// Call the function to initiate fetching web info
fetchAndUpdatePageInfo();

function updateData(){
        // Clear existing rows in all tables
        tables.forEach(tableInfo => {
            const table = document.getElementById(tableInfo.id);
            table.innerHTML = "";
        });
        populateWebInfoTables();
}

function populateWebInfoTables() {
    const serviceArray = webInfo['Services']['array'];
    //ONLY HEADING ITEMS
    const modifiedServiceArray = serviceArray.filter(item => item['category'] === 'heading');

    //ADD DESCRIPTION
    modifiedServiceArray.forEach(item => {
        if (item['category'] === 'heading') {
            // Find the first 'text' item with the same 'order'
            const descriptionItem = serviceArray.find(textItem => textItem['category'] === 'text' && textItem['order'] === item['order']);

            // If a matching 'text' item is found, set the description property as an object with 'text' and 'id'
            if (descriptionItem) {
                item.description = {
                    text: descriptionItem['text'],
                    id_info: descriptionItem['id_info'],
                };
            } else {
                item.description = {
                    text: "",
                    id_info: ""
                }; // If no matching 'text' item is found, set it to an empty object
            }
        }
    });

    //ADD ICON
    modifiedServiceArray.forEach(item => {
        // Find the associated icon in iconData by matching id_info
        const associatedIcon = iconData.find(icon => icon['associated_to_info'] === item['id_info']);

        // If an associated icon is found, add it to the heading
        if (associatedIcon) {
            item.icon = associatedIcon;
        } else {
            item.icon = {
                link: "../images_voiture/img.png"
            }
        }
    });

    tables.forEach(tableInfo => {
        const table = document.getElementById(tableInfo.id);
        let dataArray = webInfo[tableInfo.name]['array'];
        if (tableInfo.name == "Services") { dataArray = modifiedServiceArray }

        // Populate tables with new data

        dataArray.forEach(item => {
            const tableRow = document.createElement('tr');

            if (tableInfo.name == "Services") {
                const iconLink = item.icon ? item.icon.link : "../images_voiture/img.png";
                tableRow.innerHTML += `<td><img src="${iconLink}" style="height:60px; width:auto"></td>`;
            }

            tableInfo.headers.forEach(header => {
                // Handle nested properties like "description.text"
                const nestedProperties = header.split('.');
                if (nestedProperties.length > 1) {
                    tableRow.innerHTML += `<td>${item[nestedProperties[0]][nestedProperties[1]]}</td>`;
                } else {
                    tableRow.innerHTML += `<td>${item[header.toLowerCase()]}</td>`;
                }
            });

            tableRow.innerHTML += `
                <td>
                    <a href="?modify=${tableInfo.name}-${item.id_info}" class="actionbtn svg-btn" title="Modifier">
                        <img src="./src/edit_black.svg">
                    </a>
                    <a href="?delete=${tableInfo.name}-${item.id_info}" class="actionbtn svg-btn" title="Supprimer">
                        <img src="./src/delete_black.svg">
                    </a>
                </td>
            `;

            table.appendChild(tableRow);
        });
    });

    attachActionBtnListeners(modifiedServiceArray)
}



function attachActionBtnListeners(modifiedServiceArray) {
    let isPopupOpen = false
    const actionBtns = document.querySelectorAll('a.actionbtn');
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const itemId = e.currentTarget.getAttribute('href').split('-')[1];
            const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0];
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

            const itemArray = webInfo[tableId]['array'];

            const selectedItem = webInfo[tableId]['array'].find(item => item['id_info'] === itemId);
            const selectedServiceItem = modifiedServiceArray.find(item => item['id_info'] === itemId)

            if (selectedItem) {
                if (actionBtn == 'modify') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    if (tableId == "Services") {
                        serviceInfoPopup(selectedServiceItem, tableId)
                    }
                    else { webInfoPopup(selectedItem, tableId) }

                } else if (actionBtn == 'delete') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    let name = ""
                    let idKey = "id_info"
                    if (tableId == "Services") {
                        deletePopup(selectedServiceItem, tableId, name, idKey)
                    } else {
                        deletePopup(selectedItem, tableId, name, idKey)
                    }//leave as it is so that i can trigger the php commun
                }
            } else if (actionBtn == 'add') {
                if (isPopupOpen == true) {
                    popup.innerHTML = ''; // Close the popup window
                    isPopupOpen = false
                }
                if (tableId == "Services") {
                    serviceInfoPopup(selectedServiceItem, webInfo[tableId]['key'])
                }
                else { webInfoPopup(selectedItem, webInfo[tableId]['key']) }

            } else { (console.log('item2 not found')) }
        });
    });
}