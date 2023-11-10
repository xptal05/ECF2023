//define the current URL
const currentURL = window.location.pathname.split('/').pop()

//SVG mapping
function actionSvgMapping(action) {
    const svgMapping = {
        "Répondre": "reply.svg",
        "Archiver": "box-archive.svg",
        "Valider": "thumbs-up.svg",
        "Modifier": "edit_black.svg",
        "Supprimer": "delete_black.svg",
    }

    return svgMapping[action] || "edit_black.svg"
}

//status mapping according to db 10/11/2023
const statusMapping = {
    "1": "Actif",
    "2": "Nouveau",
    "3": "En attente",
    "4": "Réservé",
    "5": "Vendu",
    "6": "Archivé",
    "7": "Rejeté",
    "8": "Terminé"
}

function mapStatus(statusCode) {
    return statusMapping[statusCode] || "Unknown"
}

//Role mapping
const roleMapping = {
    "1" : "admin",
    "2" : "employee"
}

function mapRole(roleCode){
    return roleMapping[roleCode] || "Unknown"
}

//Mapping for table headers, action buttons, popup form headers and filters according to url
const customMappings = {
    'messages.php': {
        headers: {
            'Prénom': 'client_first_name',
            'Nom': 'client_last_name',
            'Email': 'client_email',
            'Téléphone': 'client_phone',
            'Sujet': 'subject',
            'Message': 'message',
            'Reçu le': 'created',
            'Statut': 'status',
            'Actions': 'id_message'
        },
        actions: ['?reply=', 'Répondre', '?archiv=', 'Archiver'],
        formHeaders : ["Nom", "Prénom", "Email", "Droits", "Statut"],
        filter: (item, filterText, tagArray, statusValue) => {
            return (
                (filterText === '' || (
                    item.client_first_name.toLowerCase().includes(filterText) ||
                    item.client_last_name.toLowerCase().includes(filterText) ||
                    item.client_email.toLowerCase().includes(filterText) ||
                    item.created.toLowerCase().includes(filterText) ||
                    item.message.toLowerCase().includes(filterText) ||
                    statusValue.toLowerCase().includes(filterText)
                )) &&
                (tagArray.length === 0 || tagArray.every(tag => (
                    item.client_first_name.toLowerCase().includes(tag) ||
                    item.client_last_name.toLowerCase().includes(tag) ||
                    item.client_email.toLowerCase().includes(tag) ||
                    item.created.toLowerCase().includes(tag) ||
                    item.message.toLowerCase().includes(tag) ||
                    statusValue.toLowerCase().includes(tag)
                )))
            )
        }
    },
    'feedback.php': {
        headers: {
            'Nom': 'client_name',
            'Note': 'rating',
            'Commentaire': 'comment',
            'Reçu le': 'created',
            'Statut': 'status',
            'Actions': 'id_feedback'
        },
        actions: ['?confirm=', 'Valider', '?archiv=', 'Archiver'],
        formHeaders : ["Nom", "Prénom", "Email", "Droits", "Statut"],
        filter: (item, filterText, tagArray, statusValue) => {
            return (
                (filterText === '' || (
                    item.client_name.toLowerCase().includes(filterText) ||
                    item.rating.toLowerCase().includes(filterText) ||
                    item.created.toLowerCase().includes(filterText) ||
                    item.comment.toLowerCase().includes(filterText) ||
                    statusValue.toLowerCase().includes(filterText)
                )) &&
                (tagArray.length === 0 || tagArray.every(tag => (
                    item.client_name.toLowerCase().includes(tag) ||
                    item.rating.toLowerCase().includes(tag) ||
                    item.created.toLowerCase().includes(tag) ||
                    item.comment.toLowerCase().includes(tag) ||
                    statusValue.toLowerCase().includes(tag)
                )))
            )
        }
    },
    'vehicles.php': {
        headers: {
            'ID': 'id',
            'Marque': 'brand',
            'Modèle': 'model',
            'Prix': 'price',
            'Année': 'year',
            'Kilomètres': 'km',
            'Image': 'img',
            'Carbourant': 'fuel',
            'Statut': 'status',
            'Actions': 'id'
        },
        actions: ['./vehicle-form.php?id=', 'Modifier', '?archiv=', 'Archiver'],
        filter: (item, filterText, tagArray, statusValue) => {
            return (
                (filterText === '' || (
                    item.brand.toLowerCase().includes(filterText) ||
                    item.model.toLowerCase().includes(filterText) ||
                    item.year.toString().toLowerCase().includes(filterText) ||
                    item.price.toString().toLowerCase().includes(filterText) ||
                    statusValue.toLowerCase().includes(filterText) ||
                    item.fuel.toLowerCase().includes(filterText)
                )) &&
                (tagArray.length === 0 || tagArray.every(tag => (
                    item.brand.toLowerCase().includes(tag) ||
                    item.model.toLowerCase().includes(tag) ||
                    item.year.toString().toLowerCase().includes(tag) ||
                    item.price.toString().toLowerCase().includes(tag) ||
                    statusValue.toLowerCase().includes(tag) ||
                    item.fuel.toLowerCase().includes(tag)
                )))
            )
        }

    },
    'user-settings.php': {
        headers: {
            'Nom': 'last_name',
            'Prénom': 'first_name',
            'Email': 'email',
            'Droits': 'role',
            "Créer le": 'active_since',
            'Statut': 'status',
            'Actions': 'id_user'
        },
        actions: ['?modify=', 'Modifier', '?delete=', 'Supprimer'],
        formHeaders : ["Nom", "Prénom", "Email", "Droits", "Statut", "Password"],
        filter: (item, filterText, tagArray, statusValue, role) => {

            return (
                (filterText === '' || (
                    item.last_name.toLowerCase().includes(filterText) ||
                    item.first_name.toLowerCase().includes(filterText) ||
                    item.email.toLowerCase().includes(filterText) ||
                    item.active_since.toLowerCase().includes(filterText) ||
                    statusValue.toLowerCase().includes(filterText) ||
                    role.toLowerCase().includes(filterText)
                )) &&
                (tagArray.length === 0 || tagArray.every(tag => (
                    item.last_name.toLowerCase().includes(tag) ||
                    item.first_name.toLowerCase().includes(tag) ||
                    item.email.toLowerCase().includes(tag) ||
                    item.active_since.toLowerCase().includes(tag) ||
                    statusValue.toLowerCase().includes(tag) ||
                    role.toLowerCase().includes(tag)
                )))
            )
        }
    },
    'web-pages.php': {
        headers: {
            'Nom': 'client_name',
            'Note': 'rating',
            'Commentaire': 'comment',
            'Reçu le': 'created',
            'Statut': 'status',
            'Actions': 'id_feedback'
        },
        actions: ['?confirm=', 'Valider', '?archiv=', 'Archiver'],
        formHeaders : ["Nom", "Prénom", "Email", "Droits", "Statut"]
    },
}

const currentMapping = customMappings[currentURL]
const tableHeaders = Object.keys(customMappings[currentURL].headers)
const formHeaders = customMappings[currentURL].formHeaders

