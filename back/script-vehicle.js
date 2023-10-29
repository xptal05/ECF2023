const mainGallery = document.getElementById('mainGallery')
const deleteImg = document.getElementById('deleteImg')

const vehicleForm = document.getElementById('vehicleForm')



function popup() {
    // Add a click event listener to the table rows (or the "Modifier" links)
    vehicleForm.addEventListener('click', (e) => {
        if (e.target.tagName === 'A' && e.target.classList.contains('action-btn')) {
            e.preventDefault();
            itemId = e.target.getAttribute('href').split('=')[1]; // Extract the ID from the link
            actionBtn = e.target.textContent
            console.log(actionBtn)
            console.log(itemId)
        }
    }
    )
}