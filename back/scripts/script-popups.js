const popup = document.getElementById('popup')

function closePopup() {
    //  if (isPopupOpen == true) {
    // Attach the closeAction event listener
    const closeBtns = document.querySelectorAll('.close-btn');
    //    const cancelButton = document.querySelector('button.btn[type="reset"]');
    closeBtns.forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            const lastPopup = popup.lastChild;
            if (lastPopup) {
                popup.removeChild(lastPopup);
                isPopupOpen = false
            }
        });
    })
    // }
}

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
    closePopup()
}

//user delete
function confirmationPopup(selectedItem) {
    const header = "Archiver l'utilisateur";
    const content = `
    <div class="popup-body-normal">
        <h3>Êtes-vous sûr de vouloir archiver cet utilisateur ?</h3>
        <form id="archivForm" class="popup-btns">
            <button class="btn error-btn close-btn" type="reset">NON</button>
            <button class="btn success-btn" type="submit">OUI</button>
        <form>
    </div>
    `;

    function eventListeners() {
        const archivForm = document.getElementById('archivForm');
        archivForm.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('supprimer');
            arraydeleteUser(selectedItem.id_user)
        });
    }

    createPopup(header, content, eventListeners);
}

//delete items
function deletePopup(selectedItem, tableId, name, idKey) {
    console.log(name)

    let tableDb
    let id = selectedItem[idKey]

    //DROPDOWNS
    if (name !== "") {
        if (metaTables.includes(dropdownKeys[tableId])) {
            tableDb = "properties_meta"
        } else { tableDb = dropdownKeys[tableId] }
    }

    //WEB SERVICES
    if (idKey === "id_info") {
        tableDb = "web_page_info"
    }

    // Create and populate the popup with the selected data
    const header = `Suppression d'élément des ${tableId}`
    const content = `
    <div class="popup-body-normal">
        <div>Êtes-vous sûr de vouloir supprimer l'élément <b>${selectedItem[name]}</b> ?</div>
        <form id="archivForm" class="popup-btns">
            <button class="btn error-btn close-btn" type="reset">NON</button>
            <button class="btn success-btn" type="submit">OUI</button>
        <form>
    </div>
    `

    function eventListeners() {
        const archivForm = document.getElementById('archivForm');

        //SUBMIT
        archivForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (selectedItem['type'] == "1") {
                deteleService(selectedItem)   //FOR SERVICES
            } else {
                arraydelete(tableDb, idKey, id)    //FOR ALL OTHER ITEMS
            }
        });
    }
    createPopup(header, content, eventListeners);

}

//Modify dropdown elements - web pages + vehicle form
function dropdownPopup(selectedItem, tableId, name, idKey) {
    console.log(selectedItem)

    let itemName = ""
    let itemDescription
    let title = "Ajouter"
    let colorRgb = "#ff0000"

    if (selectedItem) {
        itemName = selectedItem[name]
        if (tableId == "Couleur") {
            itemName = selectedItem[name].split("/")[0]
            colorRgb = selectedItem[name].split("/")[1]
        }
        itemDescription = selectedItem["description"]
        title = "Modify"
    } else if (dropdownMapping[tableId].array[0].hasOwnProperty('description')) {
        itemDescription = ""
    }

    // Create and populate the popup with the selected data
    const header = `${title} l'élément`
    let content = `
    <form id="modifyForm" class="popup-body-normal">
    <div class="popup-input-container">
        <label>Item name</label>
        <input id="itemNameInput" value="${itemName}" required>`
    if (tableId == "Couleur") {
        content += `<input type="color" id="colorPicker" name="color" value="${colorRgb}">`
    }
    content += `</div>`

    if (tableId == "Modèle") {
        const brandArray = dropdownMapping['Marque'].array
        content += `<label>Marque</label>
        <select required id="brandInput">
            <option value="0" selected disabled>Select</option>`
        for (brand of brandArray)
            content += `<option value="${brand['id_brand']}">${brand['name']}</option>`
        content += `</select>`
    }

    if (itemDescription != undefined) {
        content += `    
        <div class="popup-input-container">
            <label>Description</label>
            <input id="itemDescriptionInput" type="text" value="${itemDescription}" required>
            <p>to assign the status to vehicles, messages, feedbacks, users</p>
        </div>`
    }

    content += `
    <div class="popup-btns">
        <button class="btn" id="delete-btn">Supprimer</button>
        <button class="btn error-btn close-btn" type=reset id="close-btn">Annuler</button> 
        <button class="btn success-btn" type="submit">Sauvegarder</button>
    </div>
    </form>`


    function eventListeners() {
        if (selectedItem && tableId == "Modèle") {
            const itemBrand = selectedItem['brand']
            //select correct option odf select tag
            const brandInput = document.getElementById('brandInput')
            brandInput.value = itemBrand
            console.log('value', brandInput.value)
        }

        const popupform = document.getElementById('modifyForm')

        // Add a submit event listener to the form
        popupform.addEventListener('submit', (e) => {
            e.preventDefault();
            formType = 'dropdownForm'
            if (formvalidation(formType) == true) {
                let additionalValue
                if (tableId == "Modèle") {
                    const brandSelect = document.getElementById('brandInput');
                    additionalValue = brandSelect.value
                }
                if (tableId == "Couleur") {
                    const colorPicker = document.getElementById('colorPicker')
                    additionalValue = colorPicker.value
                }
                console.log(selectedItem)
                console.log('tableid', tableId.toLowerCase())
                dropdownpush(tableId, name, selectedItem, idKey, additionalValue)
            };
        });
    }
    createPopup(header, content, eventListeners);
}

//Web info modifications - simple
function webInfoPopup(selectedItem, serviceType) {
    console.log('selected item', selectedItem)
    let id
    let text = ""
    let type = serviceType
    let order = ""
    let category = ""

    if (selectedItem) {
        id = selectedItem['id_info']
        text = selectedItem['text']
        type = selectedItem['type']
        order = selectedItem['order']
        category = selectedItem['category']
    }

    // Create and populate the popup with the selected data
    const header = `Element Information`
    let content = `
        <form id="popupform" class="popup-body-normal">
        <div class="popup-input-container">
                <label for="text">Text</label>
                <input name="text" type="text" value="${text}" required>
            </div>
            <div class="popup-input-container">
                <label for="order">Order</label>
                <input name="order" type="number" value="${order}" required>
            </div>
        `
    if (serviceType == 3 || serviceType == 2 || serviceType == 7 || serviceType == "Contact" || serviceType == "Address" || serviceType == "Reasons") {
        content += `
        <div class="popup-input-container">
            <label for="category">Category</label>
            <input name="category" type="text" value="${category}">
        </div>`
    }
    content += `<div class="popup-btns">
                <input type="reset" class="btn error-btn close-btn" value="Reset">
                <input type="submit" class="btn success-btn" value="Submit">
            </div>`

    function eventListeners() {
        const form = document.getElementById('popupform');

        // Add a submit event listener to the form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formType = "webInfoForm"
            if (formvalidation(formType) == true) {
                // Collect data from the form
                const formData = {}
                formData.id = id
                formData.type = type

                const formInputs = document.querySelectorAll('input[type="text"], input[type="number');

                formInputs.forEach(input => {
                    formData[input.name] = input.value;
                })
                pushWebPageInfo(formData)
            }
        });
    }
    createPopup(header, content, eventListeners);
}

//WEB PAGE INFO - SERVICE modification
function serviceInfoPopup(selectedItem, serviceType) {
    console.log('selected item', selectedItem)
    let id, text, type = serviceType, order, iconLink, iconId, description, descriptionId;

    if (selectedItem) {
        id = selectedItem['id_info']
        iconLink = selectedItem['icon']['link']
        iconId = selectedItem['icon']['id_img']
        text = selectedItem['text']
        descriptionId = selectedItem['description']['id_info']
        description = selectedItem['description']['text']
        type = selectedItem['type']
        order = selectedItem['order']
    }

    // Create and populate the popup with the selected data
    const header = `Element Information`
    let content = `
        <form id="popupform" class="popup-body">
            <div class="column1">
                <div class="popup-input-container service">
                    <label for="icon">Icon</label>
                    <div id="serviceIconContainer">
                        <img src="${iconLink}">
                        <input type="text" name="img-id" hidden>
                        <a href="?modify=img" class="actionbtn">MODIFY ICON</a>
                    </div>
                </div>
                <div class="popup-input-container service">
                    <label for="order">Order</label>
                    <input name="order" type="number" value="${order}" required>
                </div>
            </div>
            <div class="column2">
                <div class="popup-input-container service">
                    <label for="text">Heading</label>
                    <input name="heading" type="text" value="${text}" required>
                </div>
                <div class="popup-input-container service">
                    <label for="description">Description</label>
                    <textarea name="description" data-value="${descriptionId}" rows="6">${description}</textarea>
                </div>
                <div class="popup-btns">
                    <input type="reset" class="btn error-btn close-btn" value="Reset">
                    <input type="submit" class="btn success-btn" value="Submit">
                </div>
            </div>
        </form>
        `
    function eventListeners() {
        const form = document.getElementById('popupform');

        // trigger pop modify drop downs
        const actionBtns = document.querySelectorAll('a.actionbtn');
        actionBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0];
                const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

                if (tableId == "img") {
                    console.log('image')
                    const gallerytype = e.currentTarget.className;
                    if (actionBtn == "add") {
                        imgAddPopup(gallerytype)
                    } else if (actionBtn == "modify") {
                        imgModifyPopup(gallerytype)
                    }
                }
            })
        })

        // Add a submit event listener to the form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (formvalidation() == true) {
                // Collect data from the form
                const formData = {}
                formData.id = id
                formData.type = type

                const formInputs = document.querySelectorAll('input[type="text"], input[type="number"]');
                const formTextarea = document.querySelectorAll('textarea')
                const icon = document.querySelector('#serviceIconContainer img')

                formInputs.forEach(input => {
                    formData[input.name] = input.value;
                })

                formTextarea.forEach(input => {
                    formData[input.name + '-id'] = input.getAttribute("data-value")
                    formData[input.name] = input.value
                })

                formData['img-id'] = icon.getAttribute("data-value")

                console.log('form data', formData)
                pushServiceInfo(formData)
            }
        });
    }
    createPopup(header, content, eventListeners);
}

//ARCHIVE ALL ELEMENTS
function archiveMessageFeedbackPopup(selectedItem, idKey, status) {
    formData = {}
    formData.id = selectedItem[idKey]
    formData.status = status
    formData.userId = UserId

    if (idKey === 'id_message') {
        formData.table = 'messages';
        formData.subject = 'subject';
    } else if (idKey === 'id_feedback') {
        formData.table = 'feedbacks';
        formData.subject = 'client_name';
    } else if (idKey === 'id') {
        formData.table = 'vehicles';
        formData.subject = 'id';
        idKey = 'id_vehicle'
    }

    formData.idColumn = idKey
    let action;

    if (status === 6) {
        action = 'archiver';
    } else if (status === 8) {
        action = 'modifier le statut de';
    } else {
        action = 'valider';
    }

    // Create and populate the popup with the selected data
    const header = `Changement d'élément des ${formData.table}`
    let content = `
    <div class="popup-body-normal">
                <div>Êtes-vous sûr de vouloir ${action} l'élément de <b>${selectedItem[formData.subject]}</b> ?</div>
            </div>
                <form id="archivForm" class="popup-btns">
                    <button class="btn error-btn close-btn" type="reset">NON</button>
                    <button class="btn success-btn" type="submit">OUI</button>
                <form>
    `

    function eventListeners() {
        // Add event listeners after appending the form to the DOM
        const archivForm = document.getElementById('archivForm');
        archivForm.addEventListener('submit', (e) => {
            e.preventDefault();
            pushMessageFeedback(formData)
        });
    }
    createPopup(header, content, eventListeners);
}

//MODIFY IMAGE POPUP -> choose from gallery or upload new
function imgModifyPopup(gallerytype) {
    // Create and populate the popup with the selected data
    const header = `Upload an Image`
    let content = `
    <div class="popup-body-normal">
            <div>Do you wish to upload a new image or choose one from the gallery?
        </div>
        <div class="popup-btns">
            <a href="?choose=img" class="actionbtn btn">CHOOSE FROM GALLERY</a>
            <a href="?add=img" class="actionbtn btn">UPLOAD NEW</a>
        </div>
    `
    function eventListeners() {
        let popupDiv = popup.lastChild;
        popupDiv.classList.add('newOrGalleryPopup')

        const actionBtns = document.querySelectorAll('a.actionbtn');
        actionBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const tableId = e.currentTarget.getAttribute('href').split('=')[1].split('-')[0];
                const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

                if (tableId == "img") {
                    console.log('image')
                    if (actionBtn == "add") {
                        imgAddPopup(gallerytype)
                    } else if (actionBtn == "choose") {
                        imgGalleryPopup(gallerytype)
                    }
                }
            })
        })
    }
    createPopup(header, content, eventListeners);
}

//UPLOAD AN IMAGE
function imgAddPopup(gallerytype) {
    let imgType = gallerytype.includes('mainCarImage') ? 'Main' : 'Gallery';

    //close the popup newOrGalleryPopup
    const newOrGalleryPopup = document.querySelector('.newOrGalleryPopup')
    if (newOrGalleryPopup) {
        newOrGalleryPopup.remove()
    }

    // Create and populate the popup with the selected data
    const header = `Upload an Image`
    let content = `
    <form method="post" enctype="multipart/form-data" id="img-form" class="popup-body-normal">
    <input type="file" name="image" id="image" fieldtext="Select a File">
    <input type="submit" name="submit" id="selectImg" value="Upload" data-value=${imgType} class="btn">
    </form>
    `
    createPopup(header, content, uploadImageAndUpdateUI);

}

//EVENT FUNCTION OF UPLOAD AN IMAGE
function uploadImageAndUpdateUI() {
    const imgForm = document.getElementById('img-form')
    const imageInput = document.getElementById('image');

    imgForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const selectedFile = imageInput.files[0];

        if (selectedFile) {
            console.log('Selected file:', selectedFile);
            const formData = new FormData();
            formData.append('image', selectedFile);

            fetch('db_query.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const submitType = document.getElementById('selectImg')
                    currentURL = window.location.pathname.split('/').pop()
                    notificationsServeur(data)

                    if (currentURL == "web-pages.php") {
                        console.log('webpages')
                        const serviceIconContainer = document.getElementById('serviceIconContainer')
                        serviceIconContainer.querySelector('img').src = data.link;
                        serviceIconContainer.querySelector('img').setAttribute('data-value', data.id_img)

                    }
                    else if (submitType.getAttribute('data-value') === "Main") { //IMG to page MAIN
                        const mainImage = document.querySelector('.img-container .car-tumbnail')
                        mainImage.src = data.link
                        mainImage.setAttribute('data-value', data.id_img)

                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    else if (submitType.getAttribute('data-value') === "Gallery") {
                        console.log(submitType.getAttribute('data-value'))
                        const galleryContainer = document.querySelector('.gallery.container');
                        const imgs = document.querySelectorAll('.gallery.container img')
                        const index = imgs.length
                        console.log(index)
                        galleryContainer.innerHTML += `
                        <img src="${data.link}" alt="Car Image" class="carGallerythumbnail" data-value="${data.id_img}" name="galleryImg${index}">
                            `;
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    })
}

function fetchImages() {
    fetchAndUpdateImageInfo()
}

//ASSIGN IMAGE ALREADY ASSIGNED POPUP
function imgSubPopup() {
    console.log('trigered')
    const imgdivs = document.querySelectorAll('.galleryImgDiv');

    imgdivs.forEach(imgdiv => {
        const associatedImage = imgdiv.querySelector('img.associated');
        imgdiv.addEventListener('click', (e) => {
            const input = imgdiv.querySelector('input.img-checkbox')

            if (associatedImage && input.checked == true) {
                const subPopup = document.getElementById('sub-popup')
                subPopup.classList.add('popup-window')
                subPopup.innerHTML = `
                <form id="reattribute-img">
                <div>Êtes-vous sûr de vouloir attribuer cette image à ce véhicule ? Elle est attribuée à un véhicule différent.</div>
                <div class="popup-btns">
                <input class="btn error-btn close-btn"  type="reset" value="Non">
                <input class="btn success-btn" type="submit" value="Oui">
                </div>
                </form>`

                const form = document.getElementById('reattribute-img')
                form.addEventListener('reset', (e) => {
                    e.preventDefault()
                    imgdiv.classList.remove('checked')
                    input.checked = false
                    subPopup.classList.remove('popup-window')
                    subPopup.innerHTML = ""
                })

                form.addEventListener('submit', (e) => {
                    e.preventDefault()
                    subPopup.classList.remove('popup-window')
                    subPopup.innerHTML = ""
                })
            }
        })

    });
}

//IMAGE GALLERY POPUP - choose from gallery
function imgGalleryPopup(gallerytype) {
    currentURL = window.location.pathname.split('/').pop()
    let imgType = ""

    //close the popup newOrGalleryPopup
    const newOrGalleryPopup = document.querySelector('.newOrGalleryPopup')
    if (newOrGalleryPopup) {
        newOrGalleryPopup.remove()
    }

    const header = `Image gallery`
    let content = `
    <div class="popup-body">
        <div class="main-gallery">
            <div class="main-gallery-header">
                <a href="" id="not-assigned">Only not assigned</a>
                <a href="" id="this-car">This car images</a>
                <a href="" id="all">All images</a>
            </div>
            <div class="main-gallery-body" id="mainGallery" data-value=${imgType}></div>
            <form id="mainImgSelect">
                <input id="imageSelectedInput" type="number" hidden value="">
                <input type="reset" class="btn error-btn close-btn" value="reset">
                <input type="submit" class="btn success-btn" value="submit" id="selectImg" data-value=${imgType}>
            </form>
        </div>
        <div class="gallery-divider"></div>
        <div class="image-info-sidebar">
        <h3>Image info</h3>
        <img src="" class="gallery-image-selected">
        <div>
        <label>Name</label>
        <input type="text" readonly value="">
        </div>
        <div>
        <label>Link</label>
        <a href="" target="_blank" rel="noreferrer">Show image in a new tab</a>
        </div>
        <div>
        <a href="" id="supprimer-img">Supprimer Image</a>
        </div>
        </div>
    </div>
    <div id="sub-popup"></div>`

    if (currentURL == "web-pages.php") {

        function getGalleryContent() {
            //POPULATE GALLERY WITH ICONS
            //fetchAndUpdateImageInfo()
            populateGallery(imageData)
            selectImgfromGallery()

        }
        createPopup(header, content, getGalleryContent);


    } else {
        function getGalleryContent() {
            fetchAndUpdateImageInfo()
            selectImgfromGallery()
        }
        createPopup(header, content, getGalleryContent);

        //Attribute the correct gallery type
        imgType = gallerytype.includes('mainCarImage') ? 'Main' : 'Gallery';
        const mainGallery = document.getElementById('mainGallery')
        mainGallery.setAttribute('data-value', imgType)
        const selectImg = document.getElementById('selectImg')
        selectImg.setAttribute('data-value', imgType)
    }

    //Attribute the correct class to gallery popup
    let popupDiv = popup.lastChild;
    popupDiv.classList.add('popup-window-gallery');
    popupDiv.classList.remove('popup-window')
}

//Filter gallery - vehicle-form
function filterGallery() {
    const notAssignedElement = document.getElementById('not-assigned');
    const thisCarElement = document.getElementById('this-car');
    const allElement = document.getElementById('all');
    let filteredImageData = []

    notAssignedElement.addEventListener('click', (e) => {
        e.preventDefault();
        filteredImageData = imageData.filter(image => image.associated_to_vehicle === null);
        populateGallery(filteredImageData);
    });

    thisCarElement.addEventListener('click', (e) => {
        const urlParams = new URLSearchParams(window.location.search);
        const vehicleId = urlParams.get('id');
        console.log(vehicleId)

        e.preventDefault();
        filteredImageData = imageData.filter(image => image.associated_to_vehicle === vehicleId);
        populateGallery(filteredImageData);
    });


    allElement.addEventListener('click', (e) => {
        e.preventDefault();
        filteredImageData = imageData;
        populateGallery(filteredImageData);
    });

    populateGallery(imageData)
}

//Populate Gallery - vehicle and web pages
function populateGallery(imageData) {
    const mainGallery = document.getElementById('mainGallery');

    //clear all images from gallery
    mainGallery.innerHTML = ""
    console.log(imageData)

    imageData.forEach(image => {
        let associated = "";
        let imgdiv = document.createElement('div');
        imgdiv.classList.add('galleryImgDiv');

        if (currentURL == "web-pages.php" && (image['type'] == 2 || image['type'] == 1)) {
            return; // Skip this image
        }

        if (currentURL !== "web-pages.php" && image['type'] == 3) {
            return; // Skip this image
        }

        if (image['associated_to_vehicle'] != null) {
            associated = "associated";
        }

        imgdiv.classList.add('galleryImgDiv');
        imgdiv.innerHTML = `
            <img src="${image['link']}" class="gallery-image ${associated}">
            <input type="checkbox" value="${image['id_img']}" class="img-checkbox">
            `
        mainGallery.appendChild(imgdiv);

        //Check box checked for images that are assigned to this vehicle
        const input = imgdiv.querySelector('input.img-checkbox');

        if (currentURL == "vehicle-form.php") {
            const urlParams = new URLSearchParams(window.location.search);
            const vehicleId = urlParams.get('id');
            if (mainGallery.getAttribute('data-value') === "Main") {
                if (image['associated_to_vehicle'] == vehicleId && image['type'] == 2) {
                    imgdiv.classList.add('checked')
                    input.checked = true
                }
            } else if (mainGallery.getAttribute('data-value') === "Gallery") {
                if (image['associated_to_vehicle'] == vehicleId && image['type'] == 1) {
                    imgdiv.classList.add('checked')
                    input.checked = true
                }
            }
        }

        //When image or check box clicked select the check box and pass the image info into the sidebar
        imgdiv.addEventListener('click', handleCheckboxClick);
        input.addEventListener('click', handleCheckboxClick);

        function handleCheckboxClick() {
            input.checked = !input.checked;
            imgdiv.classList.toggle('checked', input.checked);

            if (input.checked) {
                let checkedImgId = input.value;
                const imageIdInput = document.querySelector('.image-info-sidebar')
                let imgLink = imageIdInput.querySelector(".gallery-image-selected")
                let imageName = imageIdInput.querySelector("input")
                let actionLinks = imageIdInput.querySelectorAll("a")

                const selectedImage = imageData.find(image => image.id_img === checkedImgId);
                console.log(selectedImage)

                imgLink.src = selectedImage.link
                imageName.value = selectedImage.name
                actionLinks[0].href = selectedImage.link
                actionLinks[1].href = `?id=${selectedImage.id_img}`

                // Set the value of the input field to the checkedImgId
                const imageSelectedInput = document.getElementById('imageSelectedInput')
                imageSelectedInput.value = checkedImgId;

                //assign the delete image action to selected image
                deleteImagePopup(selectedImage)

            }

        }

    });

    imgSubPopup()

}




async function modifyUserPopup(selectedItem) {
    try {
        // Fetch and update dropdown data
        await fetchAndUpdateDropdownData();
        console.log(dropdownMapping);

        // Create and populate the popup with the selected data
        var popupDiv = document.createElement('div');
        popupDiv.id = "popupDiv"
        popupDiv.innerHTML += `
        <div class="popup-header">
            <h2>Modifier un utilisateur</h2>
            <button class="close-sign close-btn" id="close">X</button>
        <div>`;

        const popupform = document.createElement('form')
        popupform.id = "userForm"
        popupform.classList.add('popup-body-normal')

        for (const header of formHeaders) {
            console.log('header value', customMappings[currentURL].headers[header])
            const headerValue = customMappings[currentURL].headers[header];
            const divElement = document.createElement("div")
            divElement.classList.add('popup-input-container')

            if (header === "Password") {
                divElement.innerHTML += `
            <div class="btn" id="Passwordbtn" class="">Change Password</div>
            <div id="PasswordDiv" class="hidden">
                <input id="PasswordInput" value="" type="password">
                <span id="revealPassword">Show Password</span>
            </div>
            `
            } else {
                divElement.innerHTML += `<label>${header}</label>`

                if (dropdownMapping.hasOwnProperty(header)) {
                    const selectElement = document.createElement("select");
                    selectElement.id = `${header}Input`
                    const { array, idKey, name } = dropdownMapping[header];
                    console.log('dropdown', dropdownMapping[header])

                    for (const dropdownObj of array) {
                        const optionElement = document.createElement("option");
                        optionElement.value = Number(dropdownObj[idKey]);
                        optionElement.text = dropdownObj[name];
                        selectElement.appendChild(optionElement);
                    }
                    divElement.appendChild(selectElement);
                } else
                    divElement.innerHTML += `
                    <input id="${header}Input" type="text" value="${selectedItem[headerValue]}" required>`
            }

            popupform.appendChild(divElement)
        }

        popupform.innerHTML += `
        <div class="popup-btns">
            <button class="btn" id="delete-btn">Supprimer</button>
            <button class="btn error-btn close-btn" type=reset id="close-btn">Annuler</button> 
            <button class="btn success-btn" type="submit">Sauvegarder</button>
        </div>
    `;

        popup.innerHTML = ''; // Clear any existing content in the popup
        popupDiv.appendChild(popupform)
        popup.appendChild(popupDiv);
        popupDiv.classList.add('popup-window');
        isPopupOpen = true

        //select correct option odf select tag
        const selectElements = document.querySelectorAll('select');
        selectElements.forEach(selectElement => {
            const selectElementHeader = selectElement.id.replace('Input', ''); // Remove "Input" from the id
            const headerValue = customMappings[currentURL].headers[selectElementHeader];
            selectElement.value = selectedItem[headerValue];
        });

        //Event Listener Password btn
        const passwordButton = document.getElementById("Passwordbtn");
        const passwordInput = document.getElementById('PasswordInput')
        const passwordDiv = document.getElementById('PasswordDiv')

        const revealPassword = document.getElementById('revealPassword')
        passwordButton.addEventListener('click', (e) => {
            passwordButton.classList.toggle('hidden')
            passwordDiv.classList.toggle('hidden')
            passwordInput.required = true
        })
        revealPassword.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Change input type to text to reveal the password
                revealPassword.textContent = 'Hide Password'; // Change button text
            } else {
                passwordInput.type = 'password'; // Change input type back to password
                revealPassword.textContent = 'Show Password'; // Change button text
            }
        })

        // Add a submit event listener to the form
        popupform.addEventListener('submit', (e) => {
            e.preventDefault();
            const formType = 'userForm'
            if (formvalidation(formType) == true) {
                arraypush(selectedItem.id_user)
            }; // Call the arraypush function when the form is submitted
        });

        closePopup()

    } catch (error) {
        console.error('Error:', error);
    }
}


function selectImgfromGallery() {
    const submit = document.getElementById('selectImg');

    submit.addEventListener('click', (e) => {
        e.preventDefault();
        const selectedImgInput = document.querySelectorAll('.galleryImgDiv.checked');
        let selectedImgArray = [];

        if (selectedImgInput.length === 0) {
            alert('You must select at least one image');
            return;
        }

        //Web pages icons or main image on vehicle pages - handle the get id of selected image
        if (currentURL === 'web-pages.php' || submit.getAttribute('data-value') === 'Main') {
            if (selectedImgInput.length === 1) {
                const inputValue = selectedImgInput[0].querySelector('input').value;
                const url = selectedImgInput[0].querySelector('img').src;
                const imageInfo = { id: inputValue, url: url };
                selectedImgArray.push(imageInfo);

                //update UI once image selected
                if (currentURL === 'web-pages.php') {
                    const serviceIconContainer = document.getElementById('serviceIconContainer');
                    serviceIconContainer.querySelector('img').src = selectedImgArray[0].url;
                    serviceIconContainer.querySelector('img').setAttribute('data-value', selectedImgArray[0].id);
                } else if (submit.getAttribute('data-value') === 'Main') {
                    const mainImage = document.querySelector('.img-container .car-tumbnail');
                    mainImage.src = selectedImgArray[0].url;
                    mainImage.setAttribute('data-value', selectedImgArray[0].id);
                }
            } else {
                alert('You must select only one image');
            }
            //for gallery images - get all images
        } else if (submit.getAttribute('data-value') === 'Gallery') {
            selectedImgInput.forEach((imgDiv, index) => {
                const url = imgDiv.querySelector('img').src;
                const inputValue = imgDiv.querySelector('input').value;
                const imageInfo = { id: inputValue, url: url };
                selectedImgArray.push(imageInfo);
            });

            const galleryContainer = document.querySelector('.gallery.container');
            galleryContainer.innerHTML = ''; // Clear the gallery

            selectedImgArray.forEach((item, index) => {
                galleryContainer.innerHTML += `
                    <img src="${item.url}" alt="Car Image" class="carGallerythumbnail" data-value="${item.id}" name="galleryImg${index}">
                `;
            });
        }

        // Close the last popup
        const lastPopup = popup.lastChild;
        if (lastPopup) {
            popup.removeChild(lastPopup);
            isPopupOpen = false;
        }
    });
}

//delete image action
function deleteImagePopup(selectedImage) {
    const deleteImgBtn = document.getElementById('supprimer-img')

    deleteImgBtn.addEventListener('click', (e) => {
        e.preventDefault();
        // Create a confirmation pop-up
        const subPopup = document.getElementById('sub-popup');
        subPopup.classList.add('popup-window');

        // Prompt the user for confirmation in French
        subPopup.innerHTML = `
                                    <h3>Confirmer la suppression</h3>
                                    <p>Êtes-vous sûr de vouloir supprimer cette image ?</p>
                                    <div class="popup-btns">
                                        <button class="btn error-btn" id="cancel-delete">Non</button>
                                        <button class="btn success-btn" id="confirm-delete">Oui</button>
                                    </div>
                                `
        deleteImagePopupActions(selectedImage, subPopup)
    })
}

//ASSIGN EVENT LISTNERES TO CONFIRMATION DELET POPUP
function deleteImagePopupActions(selectedImage, subPopup) {
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const cancelDeleteBtn = document.getElementById('cancel-delete');

    confirmDeleteBtn.addEventListener('click', () => {
        deleteImage(selectedImage, false)

    });

    cancelDeleteBtn.addEventListener('click', () => {
        // User canceled deletion - clear the innerHTML and remove the popup window
        subPopup.innerHTML = '';
        subPopup.classList.remove('popup-window');
    });

}