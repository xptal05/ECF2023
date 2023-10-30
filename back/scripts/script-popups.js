

function closePopup() {
    // Attach the closeAction event listener
    const closeAction = document.getElementById('close');
    closeAction.addEventListener('click', () => {
        popup.innerHTML = ''; // Close the popup window
        isPopupOpen = false
    });
}

function resetPopup() {
    popupform.addEventListener('reset', (e) => {
        e.preventDefault();
        isPopupOpen = false
        popup.innerHTML = '';
    });
}

function deletePopup(selectedItem, tableId, name, idKey) {
    console.log(tableId)
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
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv"

    popupDiv.innerHTML += `

        <div class="popup-header">
            <h2>Suppression d'élément des ${tableId}</h2>
            <button class="close-sign" id="close">X</button>
        </div>
        <div class="popup-body-normal">
            <div>Êtes-vous sûr de vouloir supprimer l'élément <b>${selectedItem[name]}</b> ?</div>
            <form id="archivForm" class="popup-btns">
                <button class="btn error-btn" type="reset">NON</button>
                <button class="btn success-btn" type="submit">OUI</button>
            <form>
        </div>`;

    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    isPopupOpen = true;

    // Add event listeners after appending the form to the DOM
    const archivForm = document.getElementById('archivForm');
    archivForm.addEventListener('submit', (e) => {
        e.preventDefault();
        arraydelete(tableDb, idKey, id)
    });

    archivForm.addEventListener('reset', (e) => {
        e.preventDefault();
        isPopupOpen = false
        popupDiv.innerHTML = ''
    });
    closePopup()
}


function dropdownPopup(selectedItem, tableId, name, idKey) {
    console.log(selectedItem)

    let itemName = ""
    let itemDescription
    let title = "Ajouter"
    let brandSelect
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
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv"
    popupDiv.innerHTML += `

        <div class="popup-header">
            <h2>${title} l'élément</h2>
            <button class="close-sign" id="close">X</button>
        </div>`;

    const popupform = document.createElement('form')
    popupform.id = "modifyForm"
    popupform.classList.add('popup-body-normal')
    popupform.innerHTML = `
    <div class="popup-input-container">
        <label>Item name</label>
        <input id="itemNameInput" value="${itemName}" required>
    </div>`

    if (tableId == "Modèle") {
        const brandArray = dropdownMapping['Marque'].array
        popupform.innerHTML += `<label>Marque</label>`
        brandSelect = document.createElement('select')
        brandSelect.required = true
        brandSelect.innerHTML = `
        <option value="0" selected disabled>Select</option>`
        brandSelect.id = "brandInput"
        for (brand of brandArray)
            brandSelect.innerHTML += `    
        <option value="${brand['id_brand']}">${brand['name']}</option`
        popupform.appendChild(brandSelect)
    }

    if (itemDescription != undefined) {
        popupform.innerHTML += `    
        <div class="popup-input-container">
            <label>Description</label>
            <input id="itemDescriptionInput" type="text" value="${itemDescription}">
            <p>to assign the status to vehicles, messages, feedbacks, users</p>
        </div>`
    }

    if (tableId == "Couleur") {
        popupform.innerHTML += `  
        <input type="color" id="colorPicker" name="color" value="${colorRgb}">`
    }

    popupform.innerHTML += `
    <div class="popup-btns">
        <button class="btn" id="delete-btn">Supprimer</button>
        <button class="btn error-btn" type=reset id="close-btn">Annuler</button> 
        <button class="btn success-btn" type="submit">Sauvegarder</button>
    </div>
        `;
    popup.innerHTML = ''; // Clear any existing content in the popup
    popupDiv.appendChild(popupform)
    popup.appendChild(popupDiv);

    if (selectedItem && tableId == "Modèle") {
        const itemBrand = selectedItem['brand']
        //select correct option odf select tag
        const brandInput = document.getElementById('brandInput')
        brandInput.value = itemBrand
        console.log('value', brandInput.value)
    }


    popupDiv.classList.add('popup-window');
    isPopupOpen = true

    // Attach the closeAction event listener
    closePopup()

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

    popupform.addEventListener('reset', (e) => {
        e.preventDefault();
        isPopupOpen = false
        popup.innerHTML = '';
    });
}


function webInfoPopup(selectedItem, serviceType) {
    console.log('selected item', selectedItem)
    let id
    let text = ""
    let type = serviceType
    let order = ""

    if (selectedItem) {
        id = selectedItem['id_info']
        text = selectedItem['text']
        type = selectedItem['type']
        order = selectedItem['order']
    }

    const popup = document.getElementById('popup');
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv";
    popupDiv.innerHTML += `

        <div class="popup-header">
            <h2>Element Information</h2>
            <button class="close-sign" id="close">X</button>
        </div>
        <form id="popupform" class="popup-body-normal">
            <div class="popup-input-container">
                <label for="text">Text</label>
                <input name="text" type="text" value="${text}" required>
            </div>
            <div class="popup-input-container">
                <label for="order">Order</label>
                <input name="order" type="number" value="${order}" required>
            </div>
            <div class="popup-btns">
                <input type="reset" class="btn error-btn" value="Reset">
                <input type="submit" class="btn success-btn" value="Submit">
            </div>
        </form>`;
    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    closePopup();

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

function serviceInfoPopup(selectedItem, serviceType) {
    console.log('services popup')
    console.log('selected item', selectedItem)
    let id
    let text = ""
    let type = serviceType
    let order = ""
    let iconLink = ""
    let iconId = ""
    let description = ""

    if (selectedItem) {
        id = selectedItem['id_info']
        iconLink = selectedItem['icon']['link']
        iconId = selectedItem['icon']['id_img']
        text = selectedItem['text']
        description = selectedItem['description']
        type = selectedItem['type']
        order = selectedItem['order']
    }

    const popup = document.getElementById('popup');
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv";
    popupDiv.innerHTML += `

        <div class="popup-header">
            <h2>Element Information</h2>
            <button class="close-sign" id="close">X</button>
        </div>
        <form id="popupform" class="popup-body">
            <div class="column1">
                <div class="popup-input-container service">
                    <label for="icon">Icon</label>
                    <div id="serviceIconContainer">
                        <img src="${iconLink}">
                        <input type="text" value="${iconId}" hidden>
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
                    <input name="text" type="text" value="${text}" required>
                </div>
                <div class="popup-input-container service">
                    <label for="description">Description</label>
                    <textarea name="description" rows="6">${description}</textarea>
                </div>
                <div class="popup-btns">
                    <input type="reset" class="btn error-btn" value="Reset">
                    <input type="submit" class="btn success-btn" value="Submit">
                </div>
            </div>
        </form>`;
    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    closePopup();

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
                if (actionBtn == "delete") {
                    imgDeletePopup()
                } else if (actionBtn == "add") {
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
        if (formValidationtwo() == true) {
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
    action = status == 6 ? 'archiver' : 'valider'

    // Create and populate the popup with the selected data
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv"

    popupDiv.innerHTML += `
            <div class="popup-header">
                <h2>Changement d'élément des ${formData.table}</h2>
                <button class="close-sign" id="close">X</button>
            </div>
            <div class="popup-body-normal">
                <div>Êtes-vous sûr de vouloir ${action} l'élément de <b>${selectedItem[formData.subject]}</b> ?</div>
            </div>
                <form id="archivForm" class="popup-btns">
                    <button class="btn error-btn" type="reset">NON</button>
                    <button class="btn success-btn" type="submit">OUI</button>
                <form>`;

    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');

    // Add event listeners after appending the form to the DOM
    const archivForm = document.getElementById('archivForm');
    archivForm.addEventListener('submit', (e) => {
        e.preventDefault();
        pushMessageFeedback(formData)
    });

    closePopup()

}


// VEHICLE-FORM.php
function imgAddPopup(gallerytype) {
    const popup = document.getElementById('popup')
    // Create and populate the popup with the selected data
    var popupDiv = document.createElement('div');
    const imgType = gallerytype.includes('mainCarImage') ? 'Main' : 'Gallery';
    popupDiv.id = "popupDiv"
    popupDiv.innerHTML += `
    <div class="popup-header">
    <h2 > Upload an Image </h2> 
    <button class="close-sign" id="close">X</button>
</div>
    <form method="post" enctype="multipart/form-data" id="img-form" class="popup-body-normal">
        <input type="file" name="image" id="image" fieldtext="Select a File">
        <input type="submit" name="submit" id="selectImg" value="Upload" data-value=${imgType} class="btn">
    </form>
        `;
    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    closePopup()

    const imgForm = document.getElementById('img-form')
    const imageInput = document.getElementById('image');

    imgForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent the form from reloading the page.

        const selectedFile = imageInput.files[0];

        if (selectedFile) {
            // You have the selected file, and you can do what you need with it.
            console.log('Selected file:', selectedFile);
            const formData = new FormData(); // Create a new FormData object.
            formData.append('image', selectedFile); // Append the file to the FormData object.

            fetch('func-one.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const message = data.message;
                    console.log('Message:', message);

                    //IMG to page MAIN
                    const submitType = document.getElementById('selectImg')
                    if (submitType.getAttribute('data-value') === "Main") {
                        const mainImage = document.querySelector('.img-container .car-tumbnail')
                        mainImage.src = data.link
                        mainImage.setAttribute('data-value', data.id_img)

                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    else if (submitType.getAttribute('data-value') === "Gallery") {
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

function imgGalleryPopup(gallerytype) {
    currentURL = window.location.pathname.split('/').pop()
    if (currentURL == "web-pages.php") {
        console.log('img gallery service')
        const popup = document.getElementById('popup')
        //create new popup unstead
        var popupDiv = document.createElement('div');
        popupDiv.id = "popupDiv"
        popupDiv.innerHTML += `
    <div id="sub-popup"></div>
        <div class="popup-header">
            <h2>l'élément</h2>
            <button class="close-sign" id="close">X</button>
        </div>
        <div class="popup-body">
            <div class="main-gallery">
                <div class="main-gallery-body" id="mainGallery">
                </div>
                <form id="mainImgSelect">
                    <input id="imageSelectedInput" type="text" hidden value="">
                    <input type="reset" class="btn error-btn" value="reset">
                    <input type="submit" class="btn success-btn" value="submit" id="selectImg">
                </form>
            </div>
            <div class="gallery-divider"></div>
            <div class="image-info-sidebar"></div>
        </div>
        `;
        popup.appendChild(popupDiv);
        popupDiv.classList.add('popup-window-gallery');

        //POPULATE GALLERY WITH ICONS
        console.log(imageData)
        const mainGallery = document.getElementById('mainGallery');
        const imageSelectedInput = document.getElementById('imageSelectedInput')

        imageData.forEach(image => {
            if (image['type'] != 2 && image['type'] != 1) {
                //CREATE IMG DIV
                const imgdiv = document.createElement('div');
                imgdiv.classList.add('galleryImgDiv')
                imgdiv.innerHTML = `
                <img src="${image['link']}" class="gallery-image">
                <input type="checkbox" value="${image['id_img']}" class="img-checkbox">
                `
                mainGallery.appendChild(imgdiv);

                const input = imgdiv.querySelector('input.img-checkbox');
                imgdiv.addEventListener('click', handleCheckboxClick);
                input.addEventListener('click', handleCheckboxClick);

                function handleCheckboxClick() {
                    input.checked = !input.checked;
                    imgdiv.classList.toggle('checked', input.checked);

                    if (input.checked) {
                        let checkedImgId = input.value;
                        const imageIdInput = document.querySelector('.image-info-sidebar');
                        const selectedImage = imageData.find(image => image.id_img === checkedImgId);

                        imageIdInput.innerHTML = `
                            <h3>Image info</h3>
                            <img src="${selectedImage.link}" class="gallery-image-selected">
                            <div>
                            <label>Name</label>
                            <input type="text" readonly value="${selectedImage.name}">
                            </div>
                            <div>
                            <label>Link</label>
                            <a href="${selectedImage.link}" target="_blank" rel="noreferrer">Show image in a new tab</a>
                            </div>
                            <div>
                            <a href="?id=${selectedImage.id_img}" id="supprimer-img">Supprimer Image</a>
                            <input id="imageID" type="text" hidden value="${selectedImage.id_img}">
                            </div>`
                        // Set the value of the input field to the checkedImgId
                        imageSelectedInput.value = checkedImgId;
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
                        `;

                            const confirmDeleteBtn = document.getElementById('confirm-delete');
                            const cancelDeleteBtn = document.getElementById('cancel-delete');

                            confirmDeleteBtn.addEventListener('click', () => {
                                // User confirmed deletion - trigger the deletion action
                                fetch('func-one.php', {
                                    method: 'POST',
                                    body: JSON.stringify({ action: 'deleteImg', id_img: selectedImage.id_img, image_link: selectedImage.link }),
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        // Handle the response from the server (image deleted or not)
                                        console.log(data.message);

                                        // Clear the innerHTML and remove the popup window
                                        subPopup.innerHTML = '';
                                        subPopup.classList.remove('popup-window');
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });

                            cancelDeleteBtn.addEventListener('click', () => {
                                // User canceled deletion - clear the innerHTML and remove the popup window
                                subPopup.innerHTML = '';
                                subPopup.classList.remove('popup-window');
                            });
                        });


                        const gallerySubmit = document.querySelector('#mainImgSelect #selectImg')
                        const imageselect = document.getElementById('imageID')

                        gallerySubmit.addEventListener('click', (e) => {
                            e.preventDefault()
                            // Get the selected image
                            const selectedImage = imageData.find(image => image.id_img === imageselect.value);
                            const thirdDiv = popup.children[2];
                            const secondDiv = popup.children[1];

                            if (selectedImage) {
                                // Set the source and value for service icon
                                serviceIconContainer.querySelector('img').src = selectedImage.link;
                                serviceIconContainer.querySelector('input').value = selectedImage.id_img;
                                thirdDiv.remove()
                                secondDiv.remove()
                            } else { console.log('no image selected') }
                        })

                    }

                };
            }
        })


    } else {
        let checkedImgId
        const popup = document.getElementById('popup')
        popup.innerHTML = ""
        // Create and populate the popup with the selected data
        var popupDiv = document.createElement('div');
        console.log(gallerytype)
        const imgType = gallerytype.includes('mainCarImage') ? 'Main' : 'Gallery';
        popupDiv.id = "popupDiv"
        popupDiv.innerHTML += `
    <div id="sub-popup"></div>
        <div class="popup-header">
            <h2>l'élément</h2>
            <button class="close-sign" id="close">X</button>
        </div>
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
                    <input type="reset" class="btn error-btn" value="reset">
                    <input type="submit" class="btn success-btn" value="submit" id="selectImg" data-value=${imgType}>
                </form>
            </div>
            <div class="gallery-divider"></div>
            <div class="image-info-sidebar">
            </div>
        </div>
        `;
        popup.appendChild(popupDiv);
        popupDiv.classList.add('popup-window-gallery');


        fetchImages()
        selectImgfromGallery()
        closePopup()
    }
}

function imgSubPopup() {
    console.log('trigered')
    const imgdivs = document.querySelectorAll('.galleryImgDiv');

    imgdivs.forEach(imgdiv => {
        const associatedImage = imgdiv.querySelector('img.associated');
        imgdiv.addEventListener('click', (e) => {
            const input = imgdiv.querySelector('input.img-checkbox');
            console.log('trigereed')
            if (associatedImage) {
                // Trigger your action here for the images that meet the criteria.
                // You can access the associatedImage for further processing if needed.
                console.log('Triggered action for image with associated class:');
                const subPopup = document.getElementById('sub-popup')
                subPopup.classList.add('popup-window')
                subPopup.innerHTML = `
                <form id="reattribute-img">
                <div>Êtes-vous sûr de vouloir attribuer cette image à ce véhicule ? Elle est attribuée à un véhicule différent.</div>
                <div class="popup-btns">
                <input class="btn error-btn"  type="reset" value="Non">
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

function imgDeletePopup() {

}


function imgModifyPopup(gallerytype) {
    const popup = document.getElementById('popup')
    // Create and populate the popup with the selected data
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv"
    popupDiv.innerHTML += `

        <div class="popup-header">
                <h2>Modifier l'image</h2>
                <button class="close-sign" id="close">X</button>
        </div>
        <div class="popup-body-normal">
            <div>Do you wish to upload a new image or choose one from the gallery?
        </div>
        <div class="popup-btns">
            <a href="?choose=img" class="actionbtn btn">CHOOSE FROM GALLERY</a>
            <a href="?add=img" class="actionbtn btn">UPLOAD NEW</a>
        </div>`;
    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');

    const actionBtns = document.querySelectorAll('a.actionbtn');
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const tableId = e.target.getAttribute('href').split('=')[1].split('-')[0];
            const actionBtn = e.target.getAttribute('href').split('=')[0].replace('?', '');

            if (tableId == "img") {
                console.log('image')
                if (actionBtn == "delete") {
                    imgDeletePopup(gallerytype)
                } else if (actionBtn == "add") {
                    imgAddPopup(gallerytype)
                } else if (actionBtn == "choose") {
                    imgGalleryPopup(gallerytype)
                }
            }
        })
    })

    closePopup()
}

function fetchImages() {
    fetchAndUpdateImageInfo()

}

function populateGallery(imageData) {
    const mainGallery = document.getElementById('mainGallery');
    mainGallery.innerHTML = ""
    console.log(imageData)
    console.log(currentURL)

    imageData.forEach(image => {
        const imgdiv = document.createElement('div');
        //FOR POP UP to populate the good image type

        if (image['type'] != 3) {
            imgdiv.classList.add('galleryImgDiv');

            let associated

            if (image['associated_to_vehicle'] != null) {
                associated = "associated"
            }


            imgdiv.innerHTML = `
            <img src="${image['link']}" class="gallery-image ${associated}">
            <input type="checkbox" value="${image['id_img']}" class="img-checkbox">
        `;

            mainGallery.appendChild(imgdiv);

            const input = imgdiv.querySelector('input.img-checkbox');


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
            imgdiv.addEventListener('click', () => {
                input.checked = !input.checked;
                imgdiv.classList.toggle('checked', input.checked);


                if (input.checked) {
                    let checkedImgId = input.value;
                    const imageIdInput = document.querySelector('.image-info-sidebar');

                    const selectedImage = imageData.find(image => image.id_img === checkedImgId);
                    console.log(selectedImage)

                    imageIdInput.innerHTML = `
                <h3>Image info</h3>
                <img src="${selectedImage.link}" class="gallery-image-selected">
                <div>
                <label>Name</label>
                <input type="text" readonly value="${selectedImage.name}">
                </div>
                <div>
                <label>Link</label>
                <a href="${selectedImage.link}" target="_blank" rel="noreferrer">Show image in a new tab</a>
                </div>
                <div>
                <a href="?id=${selectedImage.id_img}" id="supprimer-img">Supprimer Image</a>
                </div>
                `
                    // Set the value of the input field to the checkedImgId
                    const imageSelectedInput = document.getElementById('imageSelectedInput')
                    imageSelectedInput.value = checkedImgId;


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
                    `;

                        const confirmDeleteBtn = document.getElementById('confirm-delete');
                        const cancelDeleteBtn = document.getElementById('cancel-delete');

                        confirmDeleteBtn.addEventListener('click', () => {
                            // User confirmed deletion - trigger the deletion action
                            fetch('func-one.php', {
                                method: 'POST',
                                body: JSON.stringify({ action: 'deleteImg', id_img: selectedImage.id_img, image_link: selectedImage.link }),
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    // Handle the response from the server (image deleted or not)
                                    console.log(data.message);

                                    // Clear the innerHTML and remove the popup window
                                    subPopup.innerHTML = '';
                                    subPopup.classList.remove('popup-window');
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        });

                        cancelDeleteBtn.addEventListener('click', () => {
                            // User canceled deletion - clear the innerHTML and remove the popup window
                            subPopup.innerHTML = '';
                            subPopup.classList.remove('popup-window');
                        });
                    });
                }

            });

            input.addEventListener('click', () => {
                input.checked = !input.checked;
                imgdiv.classList.toggle('checked', input.checked);

            });
        }
    });

    imgSubPopup()

}

function filterGallery() {
    console.log('image data before filter', imageData)
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
            <button class="close-sign" id="close">X</button>
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
            <button class="btn error-btn" type=reset id="close-btn">Annuler</button> 
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
        resetBtn()


    } catch (error) {
        console.error('Error:', error);
    }
}

//user delete
function confirmationPopup(selectedItem) {
    // Create and populate the popup with the selected data
    var popupDiv = document.createElement('div');
    popupDiv.id = "popupDiv"

    popupDiv.innerHTML += `

        <div class="popup-header">
            <h2>Archiver l'utilisateur</h2>
            <button class="close-sign" id="close">X</button>
        </div>
        <div class="popup-body-normal">
            <h3>Êtes-vous sûr de vouloir archiver cet utilisateur ?</h3>
        <form id="archivForm" class="popup-btns">
            <button class="btn error-btn" type="reset">NON</button>
            <button class="btn success-btn" type="submit">OUI</button>
        <form>
        </div>`;

    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');
    isPopupOpen = true;

    // Add event listeners after appending the form to the DOM
    const archivForm = document.getElementById('archivForm');
    archivForm.addEventListener('submit', (e) => {
        e.preventDefault();
        console.log('supprimer');
        arraydeleteUser(selectedItem.id_user)
    });

    archivForm.addEventListener('reset', (e) => {
        e.preventDefault();
        isPopupOpen = false
        popupDiv.innerHTML = ''
    });


}

function resetBtn() {
    // Attach the closeAction event listener
    const cancelButton = document.querySelector('button.btn[type="reset"]');
    cancelButton.addEventListener('click', () => {
        popup.innerHTML = ''; // Close the popup window
        isPopupOpen = false
    });
}

function selectImgfromGallery() {
    const submit = document.getElementById('selectImg')

    submit.addEventListener('click', (e) => {
        e.preventDefault()
        const selectedImgInput = document.querySelectorAll('.galleryImgDiv.checked')
        let selectedImgArray = [];

        if (selectedImgInput.length === 0) {
            alert('You must select at least one image');
        } else if (submit.getAttribute('data-value') === "Main") {
            if (selectedImgInput.length === 1) {
                //get only one image -  ineed to get the MAIN img somewhere
                const inputValue = selectedImgInput[0].querySelector('input').value;
                const url = selectedImgInput[0].querySelector('img').src;

                // Create an object to store id and url
                const imageInfo = {
                    id: inputValue,
                    url: url
                };

                // Push the object into the selectedImgArray
                selectedImgArray.push(imageInfo);

                const mainImage = document.querySelector('.img-container .car-tumbnail')

                mainImage.src = selectedImgArray[0]['url']
                mainImage.setAttribute('data-value', selectedImgArray[0]['id'])

                popup.innerHTML = ''; // Close the popup window
                isPopupOpen = false
                console.log('array', selectedImgArray)
            } else {
                alert('You must select only one image');
            }
        } else if (submit.getAttribute('data-value') === "Gallery") {
            console.log('gallery')
            selectedImgInput.forEach(imgDiv => {
                const url = imgDiv.querySelector('img').src;
                const inputValue = imgDiv.querySelector('input').value;

                // Create an object to store id and url
                const imageInfo = {
                    id: inputValue,
                    url: url
                };

                // Push the object into the selectedImgArray
                selectedImgArray.push(imageInfo);

                //REMOVE IMAGES FROM GALLERY
                // Find the container element
                const galleryContainer = document.querySelector('.gallery.container');
                // Get all the <img> elements within the container
                const images = galleryContainer.querySelectorAll('.carGallerythumbnail');
                console.log(galleryContainer)
                images.forEach(image => {
                    galleryContainer.removeChild(image);
                });

                //ADD IMAGES TO GALLERY
                selectedImgArray.forEach((item, index) => {
                    galleryContainer.innerHTML += `
                        <img src="${item.url}" alt="Car Image" class="carGallerythumbnail" data-value="${item.id}" name="galleryImg${index}">
                    `;
                });
                popup.innerHTML = ''; // Close the popup window
                isPopupOpen = false
            });
        }


    })

}