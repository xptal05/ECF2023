
const webInfo = {
    'Services': { array: [], key: 1 },
    'Address': { array: [], key: 2 },
    'Contact': { array: [], key: 3 },
    'Hours': { array: [], key: 4 },
    'About': { array: [], key: 5 },
    'Reasons': { array: [], key: 7 }
};


// Function to fetch dropdown data and update dropdownMapping
async function fetchAndUpdatePageInfo() {
    //FETCH WEB INFO
    try {
        const phpScriptURLdata = './func-one.php?action=fetchData';
        const phpScriptURLimages = './func-one.php?action=fetchData&data=images';
        // Fetch dropdown data from the server
        const webInfoData = await fetchPageInfoFromServer(phpScriptURLdata);
        const imageInfoData = await fetchPageInfoFromServer(phpScriptURLimages );
        // Group the fetched data by "type" key
        webInfoData.forEach((item) => {
            // Find the corresponding webInfo property using "key"
            for (const key in webInfo) {
                if (webInfo[key].key == item.type) {
                    webInfo[key].array.push(item);
                }
            }
        });

        imageData = imageInfoData
        iconData = []
        imageData.forEach(image => {
            if (image['type'] == 3) {
                iconData.push(image)
            }

        });

        // Now that webInfo is updated, you can perform further actions with it
        webInfolog();
    } catch (error) {
        console.log('Error fetching web info data:', error);
    }
}


// This function will fetch web info data from the server
async function fetchPageInfoFromServer(phpScriptURL) {
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

function webInfolog() {
    const serviceTbl = document.getElementById('servicetbl');
    const serviceArray = webInfo['Services']['array'];
    const modifiedServiceArray = [];


    

    // Combine 'text' into 'heading' with the same 'order'
    //add the icon for each heading, the icon['associated_to_info']= item['id_info']
    serviceArray.forEach(item => {
        if (item['category'] === 'heading') {
            // Initialize a new heading object with the 'order' property and an empty 'description'
            const newHeading = { ...item, description: "" };
    
            // Find 'text' items with the same 'order'
            const textItems = serviceArray.filter(textItem => textItem['category'] === 'text' && textItem['order'] === item['order']);
    
            // Concatenate 'text' items into the 'description' property
            textItems.forEach(textItem => {
                if (newHeading.description === "") {
                    newHeading.description = textItem['text'];
                } else {
                    newHeading.description += "<br>" + textItem['text'];
                }
            });
    
            modifiedServiceArray.push(newHeading);
        }
    });

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
                    id_info:""
                }; // If no matching 'text' item is found, set it to an empty object
            }
        }
    });

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
    console.log('nonmodified', serviceArray)
    console.log('modified', modifiedServiceArray)

// Create the table
modifiedServiceArray.forEach(item => {
    const serviceRow = document.createElement('tr');
    serviceRow.innerHTML = `
        <td><img src="${item['icon']['link']}" style="height:60px; width:auto"></td>
        <td>${item['text']}</td>
        <td>${item['description']['text']}</td>
        <td>${item['order']}</td>
        <td>
            <a href="?modify=Services-${item['id_info']}" class="actionbtn svg-btn" title="Modifier">
                <img src="./src/edit_black.svg">
            </a>
            <a href="?delete=Services-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer">
                <img src="./src/delete_black.svg">
            </a>
        </td>
    `;
    serviceTbl.appendChild(serviceRow);
});

    const hoursTbl = document.getElementById('hourstbl');
    const hoursArray = webInfo['Hours']['array'];

    hoursArray.forEach(item => {
        const hoursRow = document.createElement('tr');
        hoursRow.innerHTML += `
        <td>${item['text']}</td>
        <td>${item['order']}</td>
        <td><a href="?modify=Hours-${item['id_info']}" class="actionbtn svg-btn" title="Modifier"><img src="./src/edit_black.svg"></a><a href="?delete=Hours-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer"><img src="./src/delete_black.svg"></a></td>`;
        hoursTbl.appendChild(hoursRow);
    });

    const contactTbl = document.getElementById('contacttbl');
    const contactArray = webInfo['Contact']['array'];

    contactArray.forEach(item => {
        const contactRow = document.createElement('tr');
        contactRow.innerHTML += `
        <td>${item['text']}</td>
        <td>${item['category']}</td>
        <td>${item['order']}</td>
        <td><a href="?modify=Contact-${item['id_info']}" class="actionbtn svg-btn" title="Modifier"><img src="./src/edit_black.svg"></a><a href="?delete=Contact-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer"><img src="./src/delete_black.svg"></a></td>`;
        contactTbl.appendChild(contactRow);
    });

    const addressTbl = document.getElementById('addresstbl');
    const addressArray = webInfo['Address']['array'];

    addressArray.forEach(item => {
        const addressRow = document.createElement('tr');
        addressRow.innerHTML += `
        <td>${item['text']}</td>
        <td>${item['category']}</td>
        <td>${item['order']}</td>
        <td><a href="?modify=Address-${item['id_info']}" class="actionbtn svg-btn" title="Modifier"><img src="./src/edit_black.svg"><a href="?delete=Address-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer"><img src="./src/delete_black.svg"></a></td>`;
        addressTbl.appendChild(addressRow);
    });

    const aboutTbl = document.getElementById('abouttbl');
    const aboutArray = webInfo['About']['array'];

    aboutArray.forEach(item => {
        const aboutRow = document.createElement('tr');
        aboutRow.innerHTML += `
        <td>${item['text']}</td>
        <td><a href="?modify=About-${item['id_info']}" class="actionbtn svg-btn" title="Modifier"><img src="./src/edit_black.svg"><a href="?delete=About-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer"><img src="./src/delete_black.svg"></a></td>`;
        aboutTbl.appendChild(aboutRow);
    });

    const reasonsTbl = document.getElementById('reasonstbl');
    const reasonsArray = webInfo['Reasons']['array'];

    reasonsArray.forEach(item => {
        const reasonsRow = document.createElement('tr');
        reasonsRow.innerHTML += `
        <td>${item['text']}</td>
        <td>${item['category']}</td>
        <td>${item['order']}</td>
        <td><a href="?modify=Reasons-${item['id_info']}" class="actionbtn svg-btn" title="Modifier"><img src="./src/edit_black.svg"><a href="?delete=Reasons-${item['id_info']}" class="actionbtn svg-btn" title="Supprimer"><img src="./src/delete_black.svg"></a></td>`;
        reasonsTbl.appendChild(reasonsRow);
    });


    attachActionBtnListeners(modifiedServiceArray)
}


// Call the function to initiate fetching web info
fetchAndUpdatePageInfo();

function attachActionBtnListeners(modifiedServiceArray) {
    let isPopupOpen = false
    const actionBtns = document.querySelectorAll('a.actionbtn');
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('action')
            const itemId = e.currentTarget.getAttribute('href').split('-')[1];
            const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0];
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

            console.log('item', itemId)
            console.log('table', webInfo[tableId]['array'])
            console.log('action', actionBtn)


            const itemArray = webInfo[tableId]['array'];

            const selectedItem = webInfo[tableId]['array'].find(item => item['id_info'] === itemId);
            const selectedServiceItem = modifiedServiceArray.find(item => item['id_info'] === itemId)
            if (selectedItem) {
                if (actionBtn == 'modify') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    if(tableId == "Services"){
                        serviceInfoPopup(selectedServiceItem, tableId)
                    }
                    else{webInfoPopup(selectedItem, tableId)}
                    
                } else if (actionBtn == 'delete') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    let name = ""
                    let idKey = "id_info"
                    console.log('selected item', selectedItem)
                    if(tableId == "Services"){
                        deletePopup(selectedServiceItem, tableId, name, idKey)
                    } else{
                    deletePopup(selectedItem, tableId, name, idKey) }//leave as it is so that i can trigger the php commun
                }
            } else if (actionBtn == 'add') {
                if (isPopupOpen == true) {
                    popup.innerHTML = ''; // Close the popup window
                    isPopupOpen = false
                }
                console.log('key', webInfo[tableId]['key'])
                if(tableId == "Services"){
                    serviceInfoPopup(selectedServiceItem, webInfo[tableId]['key'])
                }
                else{webInfoPopup(selectedItem, webInfo[tableId]['key'])}
                
            } else { (console.log('item2 not found')) }
        });
    });
}

//this does not work
async function updatePageAfterAddOrDelete() {
    const serviceTbl = document.getElementById('servicetbl');
    const hoursTbl = document.getElementById('hourstbl');
    const contactTbl = document.getElementById('contacttbl');
    const addressTbl = document.getElementById('addresstbl');

    // Clear the existing data in tables
    serviceTbl.innerHTML = '';
    hoursTbl.innerHTML = '';
    // Clear other tables as well

    // Fetch and display updated data
    await fetchAndUpdatePageInfo();
}