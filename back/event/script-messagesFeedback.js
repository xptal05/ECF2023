function attachActionBtnListeners(filteredData) {
    console.log('action')
    let isPopupOpen = false
    const actionBtns = document.querySelectorAll('a.actionbtn')
    actionBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            console.log('Button clicked')
            const itemId = e.currentTarget.getAttribute('href').split('=')[1]
            const actionBtn = e.currentTarget.getAttribute('href').split('=')[0].replace('?', '')

            let idKey = ''

            // Check the URL to determine the idKey
            if (window.location.href.includes('messages.php')) {
                idKey = 'id_message'
            } else if (window.location.href.includes('feedback.php')) {
                idKey = 'id_feedback'
            }

            const selectedItem = filteredData.find((item) => item[idKey] === itemId)
            console.log('Selected Item:', selectedItem)

            if (selectedItem) {
                if (actionBtn === 'archiv') {
                    if (isPopupOpen) {
                        popup.innerHTML = '' // Close the popup window
                        isPopupOpen = false
                    }
                    
                    let status = "6"
                    archiveMessageFeedbackPopup(selectedItem, idKey, status)
                } else if (actionBtn === 'reply') {
                    let status = "8"
                    const recipientEmail = selectedItem.client_email
                    const subject = selectedItem.subject
                    const message = selectedItem.message
                    const body = '------------------<br>'+message 
                
                    openEmailClient(recipientEmail, subject, body)

                    archiveMessageFeedbackPopup(selectedItem, idKey, status)
                    //and the reply button 
                } else if(actionBtn === 'confirm') {
                    let status = "1"
                    archiveMessageFeedbackPopup(selectedItem, idKey, status)
                }

                //modify for feedbacks
            } else if (actionBtn === 'add') {
                if (isPopupOpen) {
                    popup.innerHTML = '' // Close the popup window
                    isPopupOpen = false
                }
                console.log('Key', webInfo[tableId]['key'])
                // Call the webInfoPopup function with the selected item and tableId
                archiveMessageFeedbackPopup(selectedItem, idKey) // You might want to specify the 'tableId' here.
            } else {
                console.log('Item not found')
            }
        })
    })
}


function openEmailClient(recipientEmail, subject, body) {
    const mailtoLink = `mailto:${recipientEmail}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    const anchor = document.createElement('a');
    anchor.href = mailtoLink;
    anchor.click();
    console.log('clicked')
}