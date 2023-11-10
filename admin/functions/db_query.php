<?php
session_start();
require_once "../../config/db.php";
$pdo = connectToDatabase($dbConfig);


function handleError($e)
{
    $error_message = 'Erreur: ' . $e->getMessage();

    // Log the error to a file
    $log_file = '..../error.log'; // Adjust the file path as needed
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $error_message\n";

    if (file_put_contents($log_file, $log_entry, FILE_APPEND) === false) {
        $error_message += 'Error logging the message.';
    }

    $response = ['message' => $error_message];
    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $getActions = [
        'fetchData' => 'fetchData',
        'fetchDropdowns' => 'fetchDropdowns',
        'fetchDataDashbord' => 'fetchDataDashbord',
    ];

    $action = $_GET['action'];

    // Check if the action is valid
    if (array_key_exists($action, $getActions)) {
        $functionName = $getActions[$action];

        // Check if additional data is provided in the URL
        $data = isset($_GET['data']) ? $_GET['data'] : null;

        $functionName($data);
    } else {
        echo 'Invalid action requested.';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES["image"])) {
        imgUpload();
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'];

                // Validate the CSRF token here before processing the action
        if (isset($data['csrf_token']) && $data['csrf_token'] === $_SESSION['csrf_token']) {

        $postActions = [
            'delete' => 'deleteData',
            'deleteService' => 'deleteService',
            'modifyMessageFeedback' => 'updateMessageFeedbacks',
            'modifyWeb' => 'modifyWeb',
            'newFeedback' => 'addFeedback',
            'newMessage' => 'addMessage',
            'updateVehicle' => 'updateVehicle',
            'updateUser' => 'updateUser',
            'updateDropdown' => 'updateDropdown',
            'deleteImg' => 'deleteImg',
            'modifyServices' => 'updateServices',
        ];

        if (array_key_exists($action, $postActions)) {
            $functionName = $postActions[$action];
            $functionName();
        } else {
           echoData('post action does not correspond');
        }
    } else {
       echoData('no valid token');
    }
}
} else {
    echoData('no method');
}


function sanitizeData($data) {
    if (is_array($data)) {
        // If it's an array, loop through its elements
        foreach ($data as $key => $value) {
            $data[$key] = sanitizeData($value); // Recursively sanitize each element
        }
    } else {
        // If it's not an array, sanitize the value
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
}

function echoData($parameter)
{
    $response = ['message' => 'Erreur : ' . $parameter];
    header('Content-Type: application/json');
    echo json_encode($response);
}

function deleteData()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $table = $data['table'];
    $id = $data['id'];
    $idKey = $data['idKey'];

    try {
        global $pdo;
        $sql = "DELETE FROM $table WHERE $idKey = :id;";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id); // Bind :id to the $userId variable

        if ($statement->execute()) {
            $response = ['message' => 'Succès : Element supprimé.'];
            echo json_encode($response);
        }
    } catch (PDOException $e) {
        $errorInfo = $e->errorInfo;
        if ($errorInfo[0] === '23000' && $errorInfo[1] === 1451) {
            // This error message is specific to the integrity constraint violation
            $response = ['message' => 'Erreur : Cet élément ne peut pas être supprimé car d\'autres éléments y sont liés.']; //This item cannot be deleted as other items are related to it.
        } else {
            // Handle other errors as needed
            $response = handleError($e);
        }
        echo json_encode($response);
    }
}

function deleteService()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $table = $data['table'];
    $idKey = $data['idKey'];

    $id = $data['items']['id_info']; //heading ID
    $id2 = $data['items']['description']['id_info']; //text ID
    $img_id = $data['items']['icon']['id_img']; //img id

    try {
        global $pdo;

        //dis-asociate images with the service
        $sql = "UPDATE images SET associated_to_info = null, type = 4 WHERE associated_to_info = :serviceiId AND type = 3";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':serviceiId', $id);

        if ($stmt->execute()) {
            $response = ['message' => 'Succès : Element supprimé'];

            $sql = "DELETE FROM $table WHERE $idKey = :id2;";   //DELETE TEXT
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':id2', $id2);

            if ($statement->execute()) {
                $response = ['message' => 'Succès : Element supprimé'];

                $sql = "DELETE FROM $table WHERE $idKey = :id;";  //DELETE HEADING
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id);
                if ($statement->execute()) {
                    $response = ['message' => 'Succès : Element supprimé'];
                }
            }
        }
    } catch (PDOException $e) {
        $errorInfo = $e->errorInfo;
        if ($errorInfo[0] === '23000' && $errorInfo[1] === 1451) {
            // This error message is specific to the integrity constraint violation
            $response = ['message' => 'Erreur : Cet élément ne peut pas être supprimé car d\'autres éléments y sont liés.'];
        } else {
            // Handle other errors as needed
            $response = handleError($e);
        }
    }
    echo json_encode($response);
}


function fetchData($data)
{
    try {
        global $pdo;
        $currentURL = $_SERVER['HTTP_REFERER']; // Get the current URL
        $sql = ''; // Initialize an empty SQL query
        // Determine the SQL query based on the current URL
        switch (true) {
            case strpos($currentURL, 'user-settings.php') !== false:
                $sql = 'SELECT * FROM users';
                break;

            case strpos($currentURL, 'messages.php') !== false:
                $sql = 'SELECT * FROM messages';
                break;

            case strpos($currentURL, 'feedback.php') !== false:
                $sql = 'SELECT * FROM feedbacks';
                break;

            case strpos($currentURL, 'vehicle-form.php') !== false || $data === 'images':
                $sql = 'SELECT * FROM images;';
                break;

            case strpos($currentURL, 'web-pages.php') !== false && $data !== 'images':
                $sql = 'SELECT * FROM web_page_info';
                break;

            case strpos($currentURL, 'vehicles.php') !== false || strpos($currentURL, 'catalogue.php') !== false:
                $sql = '
                        SELECT DISTINCT vehicles.id_vehicle as id, brands.name as brand, models.name as model, vehicles.status as status, vehicles.price, vehicles.year, vehicles.km, images.link as img, fuel_properties.value as fuel
                        FROM vehicles
                        LEFT JOIN brands ON vehicles.brand = brands.id_brand
                        INNER JOIN models ON vehicles.model = models.id_model
                        LEFT JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
                        LEFT JOIN (
                            SELECT vehicle_properties.*, properties_meta.*
                            FROM vehicle_properties
                            LEFT JOIN properties_meta ON vehicle_properties.property = properties_meta.id_meta
                            WHERE vehicle_properties.property_name = "Carbourant"
                        ) AS fuel_properties ON vehicles.id_vehicle = fuel_properties.vehicle';

                if (strpos($currentURL, 'catalogue.php') !== false) {
                    $sql .= ' WHERE (status = 1 OR status = 2);';
                } else {
                    $sql .= ';';
                }
                break;

            default:
                $sql = '';
        }

        if (!empty($sql)) {
            $statement = $pdo->prepare($sql);
            if ($statement->execute()) {
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                // Set the response content type to JSON
                header('Content-Type: application/json');
                // Echo the JSON-encoded data
                echo json_encode($data);
            } else {
                echo 'Une erreur est survenue';
            }
        } else {
            echo 'Invalid URL ou pas de data.';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
}

function fetchDropdowns()
{
    $SQL = [
        'statuses' => 'SELECT id_status, name, description FROM statuses',
        'user_roles' => 'SELECT id_role, name, description FROM user_roles',
        'brands' => 'SELECT id_brand, name FROM brands',
        'models' => 'SELECT id_model, name, brand FROM models',
        'caroserie' => 'SELECT id_meta, value FROM properties_meta WHERE name = "Caroserie"',
        'transmission' => 'SELECT id_meta, value FROM properties_meta WHERE name = "Transmission"',
        'carbourant' => 'SELECT id_meta, value FROM properties_meta WHERE name = "Carbourant"',
        'couleur' => 'SELECT id_meta, value FROM properties_meta WHERE name = "Couleur"',
        'portes' => 'SELECT id_meta, value FROM properties_meta WHERE name = "Portes"'
    ];

    try {
        global $pdo;
        $result = []; // Create an array to collect the data

        if (!empty($SQL)) {
            foreach ($SQL as $key => $query) {
                $statement = $pdo->prepare($query);
                if ($statement->execute()) {
                    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $result[$key] = $data; // Store data in the result array
                } else {
                    $result = 'Une erreur est survenue';
                }
            }
        } else {
            $result = 'Invalid URL ou pas de data.';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
    // Echo the JSON-encoded data
    header('Content-Type: application/json');
    echo json_encode($result);
}

function vehicle_infos()
{
    global $pdo;
    $vehicleID = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($vehicleID !== null) {
        $sql = "SELECT brands.name as brand, models.name as model, vehicles.price as price, vehicles.year as year, vehicles.km as km, images.link as img
        FROM vehicles
        LEFT JOIN brands ON vehicles.brand = brands.id_brand
        INNER JOIN models ON vehicles.model = models.id_model
        INNER JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
        WHERE vehicles.id_vehicle = :vehicleID";

        try {
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':vehicleID', $vehicleID, PDO::PARAM_INT);

            if ($statement->execute()) {
                $reponse = $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $reponse =  'Une erreur est survenue';
            }
        } catch (PDOException $e) {
            $response = handleError($e);
        }
    } else {
        $reponse = 'URL invalide ou aucune donnée à récupérer. Aucun véhicule valide sélectionné.';
    }
    // Echo the JSON-encoded data
    header('Content-Type: application/json');
    echo json_encode($reponse);
}

function imgUpload()
{

    $uploadDir = "../../uploads/";
    $response = []; // Initialize the response array.

    // Create the uploads directory if it doesn't exist.
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file_name = strip_tags($_FILES['image']['name']); //no html in filename
    $targetFile = $uploadDir . basename($file_name);       

    // Check if the file is an actual image.
    $check = getimagesize($_FILES["image"]["tmp_name"]);

    if ($check !== false && $_FILES["image"]["size"] < 1000000) {
        // Check if the file already exists.
        if (file_exists($targetFile)) {
            $response['message'] = "Erreur: Désolé, ce fichier existe déjà.". $_FILES['image']['name'];
        } else {
            // Move the uploaded file to the specified directory.
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Call imgToDB to insert data and get the image ID and link.
                $imageInfo = imgToDB($targetFile);

                if ($imageInfo) {
                    $response['message'] = "Succès: file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                    $response['id_img'] = $imageInfo['id_img'];
                    $response['link'] = str_replace("../../", "../", $imageInfo['link']);
                } else {
                    $response['message'] = "Échec de la création de l'entrée dans la base de données.";
                }
            } else {
                $response['message'] =  "Erreur: Une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        }
    } else {
        $response['message'] =  "Erreur: Le fichier n'est pas une image ou la taille de l'image dépasse 1 Mo.";
    }

    // Return the response as JSON.
    header('Content-Type: application/json');
    echo json_encode($response);
}

function imgToDB($targetFile)
{
    try {
        global $pdo;
        $imageFileName = strip_tags($_FILES['file']['name']);
        $imageFilePath = str_replace("../../", "../",$targetFile);
        $type = "4";

        $stmt = $pdo->prepare("INSERT INTO images (name, link, type) VALUES (:filename, :file_path, :type)");
        $stmt->bindParam(':filename', $imageFileName);
        $stmt->bindParam(':file_path', $imageFilePath);
        $stmt->bindParam(':type', $type);

        if ($stmt->execute()) {
            // Fetch the last inserted ID.
            $lastInsertId = $pdo->lastInsertId();

            // Fetch the link of the last inserted ID.
            $stmt = $pdo->prepare("SELECT link FROM images WHERE id_img = :lastInsertId");
            $stmt->bindParam(':lastInsertId', $lastInsertId);
            $stmt->execute();
            $row = $stmt->fetch();

            // Return an array with the image ID and link.
            return ['id_img' => $lastInsertId, 'link' => $row['link']];
        } else {
            return null;
        }
    } catch (PDOException $e) {
        return null;
    }
}


function updateImg()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $way = $data['way'];
    $id = $data['id'];
    $imageId = $data['imageId'];
    $sql = '';

    try {
        global $pdo;

        if ($way == "assign") {
            $sql = 'UPDATE images SET associated_to_vehicle =:id WHERE id_image = :imageId';
        } else if ($way == "unassign") {
            //UNASSIGN IMG
            $sql = 'UPDATE images SET associated_to_vehicle = null WHERE id_image = :imageId';
        }
        if (!empty($sql)) {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':imageId', $imageId);
            if ($stmt->execute()) {
                if ($stmt->execute()) {
                    $response = ['message' => 'Succès : L\'image a été mise à jour avec succès.'];
                }
            }
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function updateMessageFeedbacks()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $table = $data['table'];
    $idName = $data['idColumn'];
    $itemId = $data['id'];
    $status = $data['status'];
    $userId = $data['userId'];

    try {
        global $pdo;
        $sql = "UPDATE $table SET status =:status, modified = NOW(), modified_by =:userId WHERE $idName =:itemID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':itemID', $itemId);

        if ($stmt->execute()) {
            $response = ['message' => 'Succès : Nous avons bien pris en compte votre message'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}


function addFeedback()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $userId = $data['userId'];
    $clientName = $data['client_name'];
    $rating = $data['rating'];
    $comment = $data['comment'];
    $status = 2;

    try {
        global $pdo;
        $sql = "";
        if ($userId == "") {
            $sql = "INSERT INTO feedbacks (client_name, rating, comment, created, status) 
            VALUES (:clientName, :rating, :comment, NOW(), :status)";
        } else {
            $sql = "INSERT INTO feedbacks (client_name, rating, comment, created, modified_by, status) 
                VALUES (:clientName, :rating, :comment, NOW(), :userId, :status)";
        }
        $stmt = $pdo->prepare($sql);
        if ($userId != "") {
            $stmt->bindParam(':userId', $userId);
        }
        $stmt->bindParam(':clientName', $clientName);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $response = ['message' => 'Succès : Témoignage ajoutée avec succès.'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function addMessage()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $lastName = $data['lastname'];
    $firstName = $data['firstname'];
    $email = $data['email'];
    $status = $data['status'];
    $phone = $data['phone'];
    $subject = $data['subject'];
    $message = $data['message'];

    try {
        global $pdo;
        $sql = "INSERT INTO messages (client_first_name	, client_last_name	, client_email, client_phone, message, created, status, subject) 
            VALUES (:firstname, :lastname, :email, :phone, :message, NOW(), :status, :subject)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstname', $firstName);
        $stmt->bindParam(':lastname', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':subject', $subject);

        if ($stmt->execute()) {
            $response = ['message' => 'Succès : Message ajouté avec succès.'];
        } else {
            $response = ['message' => 'Erreur : Échec de l\'ajout du message.'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function modifyWeb()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $id = $data['id'];
    $type = $data['type'];
    $text = $data['text'];
    $order = $data['order'];
    $category = $data['category'];
    $sql = "";

    try {
        global $pdo;
        if (isset($id)) { //UPDATE INFO
            $sql = "UPDATE web_page_info SET type = :type, text = :text, `order` = :order, category = :category WHERE id_info = :id";
        } else { //INSERT INFO
            $sql = "INSERT INTO web_page_info (type, text, `order`, category) VALUES (:type, :text, :order, :category)";
        }
        $stmt = $pdo->prepare($sql);
        if (isset($id)) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':order', $order);
        $stmt->bindParam(':category', $category);

        if ($stmt->execute()) {
            $response = ['message' => 'Succès : Informations de la page Web mises à jour avec succès.'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function updateServices()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $id = $data['id'];
    $type = $data['type'];
    $text = $data['heading'];
    $order = $data['order'];

    $id_text = $data['description-id'];
    $text_text = $data['description'];

    $imgId = ($data['img-id'] == null) ? 0 : $data['img-id'];
    $sql = "";

    try {
        global $pdo;
        if (isset($id)) { //UPDATE INFO
            $sql = "UPDATE web_page_info SET type = :type, text = :text, `order` = :order WHERE id_info = :id";
        } else { //INSERT INFO
            $sql = "INSERT INTO web_page_info (type, text, `order`, category) VALUES (:type, :text, :order, 'heading')";
        }
        $stmt = $pdo->prepare($sql);
        if (isset($id)) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':order', $order);

        if ($stmt->execute()) {
            $serviceId = ($id === null) ? $pdo->lastInsertId() : $id;

            if ($id_text != "") { //UPDATE INFO
                $sql = "UPDATE web_page_info SET type = :type, text = :text, `order` = :order WHERE id_info = :id_text";
            } else { //INSERT INFO
                $sql = "INSERT INTO web_page_info (type, text, `order`, category) VALUES (:type, :text, :order, 'text')";
            }
            $stmt = $pdo->prepare($sql);
            if ($id_text != "") {
                $stmt->bindParam(':id_text', $id_text);
            }
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':text', $text_text);
            $stmt->bindParam(':order', $order);

            if ($stmt->execute()) {
                $response = ['message' => 'Succès : Informations de la page Web mises à jour avec succès.'];
            }

            if ($imgId !== 0) {
                //dis-asociate images with the service
                $sql = "UPDATE images SET associated_to_info = null, type = 4 WHERE associated_to_info = :serviceiId AND type = 3";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':serviceiId', $serviceId);
                if ($stmt->execute()) {

                    // Insert MainImg into the images table
                    $sql = "UPDATE images SET associated_to_info = :serviceiId, type = 3 WHERE id_img = :mainImage";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':mainImage', $imgId);
                    $stmt->bindParam(':serviceiId', $serviceId);
                    if ($stmt->execute()) {
                        $response = ['message' => 'Succès : Informations de la page Web mises à jour avec succès.'];
                    }
                }
            }
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function fetchDataDashbord()
{
    try {
        global $pdo;
        $data = array();
        $tableNames = ['vehicles', 'messages', 'feedbacks'];

        foreach ($tableNames as $tableName) {
            // Generate a dynamic SQL query for each table
            $sql = "SELECT * FROM $tableName";
            $statement = $pdo->prepare($sql);

            if ($statement->execute()) {
                // Store the results in the data array with the table name as the key
                $data[$tableName] = $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo 'Une erreur est survenue';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
}

function updateVehicle()
{
    $userId = $_SESSION["user_id"];
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);

    try {
        global $pdo;
        $id = $data['id'];

        // Begin a transaction
        $pdo->beginTransaction();

        if (isset($id)) {
            // Update vehicle data
            $sql = 'UPDATE vehicles SET 
            status = :status, year = :year, price = :price, brand = :brand, model = :model, km = :km, conformity = :conformity, modified = NOW(), modified_by = :userId, consumption = :consumption, other_equipment = :other_equipment
            WHERE id_vehicle = :id';
        } else {
            $sql = 'INSERT INTO vehicles (status, year, price, brand, model, km, conformity, created, modified_by, consumption, other_equipment) 
            VALUES (:status, :year, :price, :brand, :model, :km, :conformity, NOW(), :userId, :consumption, :other_equipment)';
        }
        // Prepare and execute the SQL update statement
        $statement = $pdo->prepare($sql);
        if (isset($id)) {
            $statement->bindParam(':id', $id);
        }
        $statement->bindParam(':price', $data['Price']);
        $statement->bindParam(':year', $data['year']);
        $statement->bindParam(':km', $data['km']);
        $statement->bindParam(':model', $data['Modèle']);
        $statement->bindParam(':brand', $data['Marque']);
        $statement->bindParam(':conformity', $data['Conformity']);
        $statement->bindParam(':status', $data['Statut']);
        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':other_equipment', $data['other']);
        $statement->bindParam(':consumption', $data['Consumption']);

        if ($statement->execute()) {
            // Get the new vehicle ID after insertion (if it's an INSERT operation)
            $vehicleId = ($id === null) ? $pdo->lastInsertId() : $id;

            // Handle vehicle property updates
            $propertyMappings = [
                'Carrosserie' => 'Caroserie',
                'Caroserie' => 'Carrosserie',
                'Couleur' => 'Couleur',
                'Portes' => 'Portes',
                'Transmission' => 'Transmission',
                'Carbourant' => 'Carbourant',
                'Options' => 'Options'
            ];

            // Iterate through the properties to keep and update them
            foreach ($data as $propertyName => $propertyValue) {
                if (isset($propertyMappings[$propertyName])) {
                    $actualPropertyName = $propertyMappings[$propertyName];

                    if (isset($id)) {
                        $sql = "UPDATE vehicle_properties SET property = :propertyValue WHERE property_name = :actualPropertyName AND vehicle = :id";
                    } else {
                        $sql = "INSERT INTO vehicle_properties (property_name, property, vehicle) VALUES (:actualPropertyName, :propertyValue, :id)";
                    }

                    // Create a new PDO statement for each property update
                    $stmt = $pdo->prepare($sql);

                    // Bind parameters
                    $stmt->bindValue(':propertyValue', $propertyValue);
                    $stmt->bindValue(':actualPropertyName', $actualPropertyName);
                    $stmt->bindValue(':id', $vehicleId);

                    if ($stmt->execute()) {

                        //IMAGES
                        $mainImage = $data['mainImg'];
                        $galleryImages = [];
                        foreach ($data as $key => $value) {
                            // Check if the key starts with "GalleryImg"
                            if (preg_match('/^galleryImg\d+$/', $key)) {
                                $galleryImages[] = $value;
                            }
                        }

                        // || !empty($galleryImages)


                        //dis-asociate images with the car
                        $sql = "UPDATE images SET associated_to_vehicle = null, type = 4 WHERE associated_to_vehicle = :vehicleId AND type = 2";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':vehicleId', $vehicleId);
                        if ($stmt->execute()) {

                            // Insert MainImg into the images table
                            $sql = "UPDATE images SET associated_to_vehicle = :vehicleId, type = 2 WHERE id_img = :mainImage";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':mainImage', $mainImage);
                            $stmt->bindParam(':vehicleId', $vehicleId);
                            if ($stmt->execute()) {

                                // Insert GalleryImg items into the images table
                                foreach ($galleryImages as $galleryImage) {
                                    $sql = "UPDATE images SET associated_to_vehicle = :vehicleId, type = 1 WHERE id_img = :galleryImage";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':galleryImage', $galleryImage);
                                    $stmt->bindParam(':vehicleId', $vehicleId);
                                    if (!$stmt->execute()) {
                                        // Roll back the transaction and return an error response
                                        $pdo->rollBack();
                                        $response = ['message' => 'Error: Échec de la mise à jour des propriétés du véhicule.'];
                                        echo json_encode($response);
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Commit the transaction if all statements were successful
            $pdo->commit();
            $response = ['message' => 'Succès: Données et propriétés du véhicule mises à jour avec succès.'];
            echo json_encode($response);
        } else {
            // Roll back the transaction and return an error response
            $pdo->rollBack();
            $response = ['message' => 'Error: Échec de la mise à jour des données du véhicule.'];
            echo json_encode($response);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
    }
}


function updateUser()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);
    $lastName = $data['Nom'];
    $firstName = $data['Prénom'];
    $email = $data['Email'];
    $rights = $data['Droits'];
    $userId = $data['id'];
    $statusUser = $data['Statut'];
    $password = trim($data['Password']); // Move this line outside the if statement
    $cost = 12;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    $sql = '';

    if (isset($userId)) {
        $sql = 'UPDATE users SET last_name = :lastName, first_name = :firstName, email = :email, role = :rights, status = :status';
        if (!empty($password)) {
            $sql .= ', password = :password';
        }
        $sql .= ' WHERE id_user = :userId';
    } else {
        $sql = 'INSERT INTO users (last_name, first_name, email, role, status, password, active_since) VALUES (:lastName, :firstName, :email, :rights, :status, :password, NOW())';
    }

    try {
        global $pdo;
        // Prepare and execute the SQL update statement
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':lastName', $lastName);
        $statement->bindParam(':firstName', $firstName);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':rights', $rights);
        $statement->bindParam(':status', $statusUser);
        if (isset($userId)) {
            $statement->bindParam(':userId', $userId);
        }
        if (!empty($password)) {
            $statement->bindParam(':password', $hashedPassword);
        }
        if ($statement->execute()) {
            $response = ['message' => 'Success : Utilisateur mis à jour avec succès.'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}

function updateDropdown()
{
    $dataFetched = json_decode(file_get_contents('php://input'), true);
    $data = sanitizeData($dataFetched);

    // Extract data
    $idColumn =  $data['idKey'];
    $nameColumn = $data['name'];
    $itemName = $data['itemName'];
    $id = $data['id'];
    $itemDescription = $data['itemDescription'];
    $table = $data['table'];

    $metaName = $data['metaName'];
    $brandSelect = $data['brandSelect'];
    $metaTables = [
        "caroserie",
        "carbourant",
        "couleur",
        "portes",
        "transmission"
    ];

    if (isset($id)) {
        if (!in_array($table, $metaTables) || $table !== "properties_meta") {
            if ($itemDescription != '') {
                $sql = "UPDATE " . $table . " SET `" . $nameColumn . "` = :itemName, `description` = '" . $itemDescription . "' WHERE " . $idColumn . " = :id";
            } else if ($brandSelect != '') {
                $sql = "UPDATE " . $table . " SET " . $nameColumn . " = :itemName, brand ='" . $brandSelect . "' WHERE " . $idColumn . " = :id"; //funguje
            } else {
                $sql = "UPDATE " . $table . " SET `" . $nameColumn . "` = :itemName WHERE `" . $idColumn . "` = :id";
            }
        } else {
            $sql = "UPDATE properties_meta SET `" . $nameColumn . "` = :itemName WHERE '" . $idColumn . "' = :id";
        }
    } else {
        if ($table != "properties_meta") {
            if ($itemDescription != '') {
                $sql = "INSERT INTO " . $table . " (" . $nameColumn . ", description) VALUES (:itemName , '" . $itemDescription . "')";
            } else if ($brandSelect != '') {
                $sql = "INSERT INTO " . $table . " (" . $nameColumn . ", brand) VALUES (:itemName ," . $brandSelect . ")";
            } else {
                $sql = 'INSERT INTO ' . $table . ' (' . $nameColumn . ') VALUES (:itemName)';
            }
        } else {
            $sql = "INSERT INTO  properties_meta (" . $nameColumn . ", name) VALUES (:itemName,'" . $metaName . "')";
        }
    }

    try {
        global $pdo;
        // Prepare and execute the SQL update statement
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':itemName', $itemName);

        if (isset($id)) {
            $statement->bindParam(':id', $id);
        }

        if ($statement->execute()) {
            $response = ['message' => 'Succès: Element ' . (isset($id) ? 'mise a jour' : 'inseré') . ' avec succès.'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function deleteImg()
{
    try {
        global $pdo;
        $dataFetched = json_decode(file_get_contents('php://input'), true);
        $data = sanitizeData($dataFetched);
        $idImg = $data['id_img'];
        $imageLink = "../".$data['image_link']; 

        // First, delete the image file from the server's file system.
        if (file_exists($imageLink) && unlink($imageLink)) {
            // delete the database record.
            $stmt = $pdo->prepare("DELETE FROM images WHERE id_img = :id_img");
            $stmt->bindParam(':id_img', $idImg);
            if($stmt->execute()) {

            $response = (["message" => "Succès : Image et enregistrement supprimés avec succès."]);
            }
        } else {
            $response = (["message" => "Erreur : Échec de la suppression du fichier image ou il n'existe pas." . $imageLink]);
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}