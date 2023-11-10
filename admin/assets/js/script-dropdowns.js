
const dropdownMapping = {
    "Droits": { array: [], idKey: "id_role", name: "name" },
    "Statut": { array: [], idKey: "id_status", name: "name" },
    "Marque": { array: [], idKey: "id_brand", name: "name" },
    "Modèle": { array: [], idKey: "id_model", name: "name" },
    "Carrosserie": { array: [], idKey: "id_meta", name: "value" },
    "Carbourant": { array: [], idKey: "id_meta", name: "value" },
    "Couleur": { array: [], idKey: "id_meta", name: "value" },
    "Portes": { array: [], idKey: "id_meta", name: "value" },
    "Transmission": { array: [], idKey: "id_meta", name: "value" },
};

const dropdownKeys = {
    "Droits": "user_roles",
    "Statut": "statuses",
    "Marque": "brands",
    "Modèle": "models",
    "Carrosserie": "caroserie",
    "Carbourant": "carbourant",
    "Couleur": "couleur",
    "Portes": "portes",
    "Transmission": "transmission"
};

//call function according to the page location
function pageDropdownsActions() {
    const currentUrl = window.location.pathname.split('/').pop()

    if (currentUrl == "user-settings.php") { return dropdownMapping }
    else if (currentUrl == "settings.php") { dropdowntopage() }
    else if (currentUrl == "upload-img.php") { dropdowntopage() }
    else if (currentUrl == "vehicle-form.php") {
        optionLoop()
        hiddenOptions()
    }

}

