function notificationsServeur(data) {
    notifications.classList.toggle('on')
    if (data['message'].startsWith('Erreur')) {
        notifications.classList.add('error')
    } else if (data['message'].startsWith('Succès')) {
        notifications.classList.add('success')
    }
    notifications.innerHTML = `${data['message']}<div class="notification-progress-bar"></div>`
    popup.innerHTML = '';
    fetchDataAndRenderList()
    setTimeout(function () {
        notifications.className = '';
    }, 8000);
}