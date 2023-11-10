function notificationsServeur(data) {
    notifications.classList.toggle('on')
    if (data['message'].startsWith('Erreur')) {
        notifications.classList.add('error')
    } else if (data['message'].startsWith('Succès')) {
        notifications.classList.add('success')
    }
    notifications.innerHTML = `${data['message']}<div class="notification-progress-bar"></div>`

    if (!notifications.classList.contains('error')) {
        // Close the last popup
        const lastPopup = popup.lastChild;
        if (lastPopup) {
            popup.removeChild(lastPopup);
            isPopupOpen = false;
        }


        //Update data in UI
        const currentUrl = window.location.pathname.split('/').pop()
        if (currentUrl === "web-pages.php") {
            fetchAndUpdatePageInfo()
        } else if (currentUrl === "settings.php" || currentUrl === "vehicle-form.php") {
            fetchAndUpdateDropdownData()
        } else {
            fetchDataAndRenderList()
        }
    }
    setTimeout(function () {
        notifications.className = ''
    }, 8000)
}