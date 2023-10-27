function formvalidation() {
    return true
}

function pushNewMessage(formData, messageForm) {
    const phpScriptURL = '../BACK/func-one.php?action=newMessage';
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
        // Handle the response from the server
        // Show a success message or redirect to a success page
      //  notificationsServeur(data)
        console.log(data)
        messageForm.reset()
    })

    .catch((error) => {
        console.error("Error:", error);
        console.log(data)

    });


}

function messageEvent(){

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
