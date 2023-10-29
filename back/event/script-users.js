let isPopupOpen = false

function attachActionBtnListeners(filteredData) {

    console.log('array is')
    const actionBtns = document.querySelectorAll('a.actionbtn');

    // Add a click event listener to the table rows (or the "Modifier" links)
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const itemId = e.currentTarget.getAttribute('href').split('=')[1]; // Extract the ID from the link
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');
            console.log(actionBtn)
            console.log('item id', itemId)

            // Fetch the corresponding data from your 'data' array
            const selectedItem = filteredData.find(item => item[customMappings[currentURL].headers["Actions"]] === itemId);
console.log(selectedItem)

            if (selectedItem) {

                if (actionBtn == 'modify') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    modifyUserPopup(selectedItem)

                } else if (actionBtn == 'delete') {
                    if (isPopupOpen == true) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false
                    }
                    //deletePopup(selectedItem, tableId, name, idKey)
                    confirmationPopup(selectedItem)
                }

                if (isPopupOpen == true) {
                    // Attach the closeAction event listener
                    const closeAction = document.getElementById('close');
                    closeAction.addEventListener('click', () => {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false

                    });
                }

            }
        })
    })
}

const userForm = document.getElementById('userForm')
// Add a submit event listener to the form
userForm.addEventListener('submit', (e) => {
    e.preventDefault();
    if (formvalidation() == true) {
        arraypush()
    }; // Call the arraypush function when the form is submitted
});