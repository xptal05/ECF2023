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
        popup.innerHTML = ''
    }
    setTimeout(function () {
        notifications.className = ''
    }, 8000)
}

function formvalidation() {

    let valid = true

    // Validate Last Name
    const lastname = document.getElementById("lastname");
    if (lastname.value.trim() === "") {
        alert("Nom est requis.");
        valid = false
    }

    // Validate First Name
    const firstname = document.getElementById("firstname");
    if (firstname.value.trim() === "") {
        valid = false;
        alert("Prénom est equis.");
    }

    // Validate Email
    const email = document.getElementById("email");
    if (email.value.trim() === "") {
        valid = false;
        alert("Email est equis.");
    } else if (!isValidEmail(email.value)) {
        valid = false;
        alert("Veuillez entrer une adresse e-mail valide.");
    }

    // Validate Phone
    const phone = document.getElementById("phone");
    if (phone.value.trim() === "") {
        valid = false;
        alert("Téléphone est requis.");
    } else if (!isValidPhoneNumber(phone.value)) {
        valid = false;
        alert("Veuillez entrer un numéro de téléphone valide. Format accepté : (+33612345678 ou 0612345678).");
    }

    // Validate Message
    const message = document.getElementById("message");
    if (message.value.trim() === "") {
        valid = false;
        alert("Message est requis.");
    }


    function isValidEmail(email) {
        // Use a regular expression to validate the email format
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailRegex.test(email);
    }

    function isValidPhoneNumber(phone) {
        // Use a regular expression to validate the phone number format
        const phoneRegex = /^(\+[0-9]+|0[0-9]{9})$/;
        return phoneRegex.test(phone);
    }
    
    return valid
}

function pushNewMessage(formData, messageForm) {
    const phpScriptURL = './back/db_query.php?action=newMessage'
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
            notificationsServeur(data)
            messageForm.reset()
        })

        .catch((error) => {
            console.error("Error:", error)
        })
}

function messageEvent() {
    const messageForm = document.getElementById('messageForm')
    // Add a submit event listener to the form
    messageForm.addEventListener('submit', (e) => {
        e.preventDefault()

        if (formvalidation() == true) {
            let formData = {}

            // Iterate through all input and select elements
            messageForm.querySelectorAll('input, textarea').forEach(input => {
                // Handle other input and select elements
                formData[input.id.replace("Input", "")] = input.value
            })

            formData.status = 2
            pushNewMessage(formData, messageForm)
        } // Call the arraypush function when the form is submitted
    })
}