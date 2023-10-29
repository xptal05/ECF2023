function notificationsServeur(data) {
    const currentURL = window.location.pathname.split('/').pop()

    notifications.classList.toggle('on')
    if (data['message'].startsWith('Erreur')) {
        notifications.classList.add('error')
    } else if (data['message'].startsWith('Succès')) {
        notifications.classList.add('success')
    }
    notifications.innerHTML = `${data['message']}<div class="notification-progress-bar"></div>`
    if (currentURL != 'index.php') {
        const popup = document.getElementById('popup')
        popup.innerHTML = '';
    }
    setTimeout(function () {
        notifications.className = '';
    }, 8000);
}

function formvalidation() {
    return true
}

function pushNewMessage(formData, messageForm) {
    const phpScriptURL = './back/db_query.php?action=newMessage';
    formData.action = 'newMessage'

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
            console.log(data)
            notificationsServeur(data)
            messageForm.reset()
        })

        .catch((error) => {
            console.error("Error:", error);
            console.log(data)

        });


}

function messageEvent() {

    const messageForm = document.getElementById('messageForm')
    // Add a submit event listener to the form
    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (formvalidation() == true) {
            let formData = {}

            // Iterate through all input and select elements
            messageForm.querySelectorAll('input, textarea').forEach(input => {
                // Handle other input and select elements
                formData[input.id.replace("Input", "")] = input.value;
            });

            formData.status = 2
            console.log(formData)
            pushNewMessage(formData, messageForm)
        } // Call the arraypush function when the form is submitted
    });
}