function notificationsServeur(data) {
    console.log('notif')
    notifications.classList.toggle('on')
    if (data['message'].startsWith('Erreur')) {
        notifications.classList.add('error')
    } else if (data['message'].startsWith('Succès')) {
        notifications.classList.add('success')
    }
    notifications.innerHTML = `${data['message']}<div class="notification-progress-bar"></div>`
    popup.innerHTML = '';
    const currentUrl = window.location.pathname.split('/').pop()

    if(currentUrl ==="web-pages.php"){
        fetchAndUpdatePageInfo()
    } else if(currentUrl ==="settings.php" ||currentUrl ==="vehicle-form.php" ){
        fetchAndUpdateDropdownData()
    }else{
        fetchDataAndRenderList()
    }
    setTimeout(function () {
        notifications.className = '';
    }, 8000);

    if(currentUrl ==="vehicle-form.php" ){
        setTimeout(function () {window.location.reload();},4000)
    }
}