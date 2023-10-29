function attachActionBtnListeners(filteredData) {
    let isPopupOpen = false;
    const actionBtns = document.querySelectorAll('a.actionbtn');
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {

            console.log('Button clicked');
            const itemId = e.currentTarget.getAttribute('href').split('=')[1];
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '');

            let idKey = 'id'; // Initialize idKey

            const selectedItem = filteredData.find((item) => item[idKey] === itemId);
            console.log('Selected Item:', selectedItem);

            if (selectedItem) {
                if (actionBtn === 'archiv') {
                    e.preventDefault();
                    if (isPopupOpen) {
                        popup.innerHTML = ''; // Close the popup window
                        isPopupOpen = false;
                    }
                    // Call the webInfoPopup function with the selected item
                    let status = "6"
                   archiveMessageFeedbackPopup(selectedItem, idKey, status);
                   console.log('archived')

                } else {
                    console.log('Item not found');
                }
            }
        });
    });
}



//archiveMessageFeedbackPopup(parametres)
