<?php
require_once "./config/db.php";

$pdo = connectToDatabase($dbConfig);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'fetchData':
            fetchData();
            break;
        case 'fetchDropdowns':
            fetchDropdowns();
            break;
        case 'fetchDataDashbord':
            fetchDataDashbord();
            break;
        default:
            echo 'Invalid action requested.';
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES["image"])) {
        imgUpload();
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'];
        switch ($action) {
            case 'delete':
                deleteData();
                break;
            case 'modifyMessageFeedback':
                updateMessageFeedbacks();
                break;
            case 'modifyWeb':
                modifyWeb();
                break;
            case 'newFeedback':
                addFeedback();
                break;
            case 'newMessage':
                addMessage();
                break;
            case 'updateVehicle':
                updateVehicle();
                break;
            case 'updateUser':
                updateUser();
                break;
            case 'updateDropdown':
                updateDropdown();
                break;
            case 'deleteImg':
                deleteImg();
                break;
            default:
                echoData();
                break;
        }
    }
} else {
    echoData();
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

function fetchData()
{
    $user = 'studi';
    $password = 'studi-ecf';
    $db = 'studi_ecf';
    $host = 'localhost';
    $port = 3001;

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $currentURL = $_SERVER['HTTP_REFERER']; // Get the current URL

        $sql = ''; // Initialize an empty SQL query

        // Determine the SQL query based on the current URL
        if (strpos($currentURL, 'user-settings.php') !== false) {
            $sql = 'SELECT * FROM users';
        } elseif (strpos($currentURL, 'messages.php') !== false) {
            $sql = 'SELECT * FROM messages';
        } elseif (strpos($currentURL, 'feedback.php') !== false) {
            $sql = 'SELECT * FROM feedbacks';
        } elseif (strpos($currentURL, 'vehicles.php') !== false) {
            $sql = 'SELECT DISTINCT vehicles.id_vehicle as id, brands.name as brand, models.name as model, vehicles.status as status, vehicles.price, vehicles.year, vehicles.km, images.link as img, fuel_properties.value as fuel
            FROM vehicles
            LEFT JOIN brands ON vehicles.brand = brands.id_brand
            INNER JOIN models ON vehicles.model = models.id_model
            LEFT JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
            LEFT JOIN (
                SELECT DISTINCT vehicle, value
                FROM vehicle_properties
                INNER JOIN properties_meta ON vehicle_properties.property = properties_meta.id_meta
                WHERE properties_meta.name = "Carburant"
            ) AS fuel_properties ON vehicles.id_vehicle = fuel_properties.vehicle;
        ';
        } else if (strpos($currentURL, 'vehicle-form.php') !== false || $_GET['data'] === 'images') {
            $sql = 'SELECT * FROM images;';
        } else if (strpos($currentURL, 'web-pages.php')  !== false && $_GET['data'] !== 'images') {
            $sql = 'SELECT * FROM web_page_info';
        } else if (strpos($currentURL, 'catalogue.php') !== false) {
            $sql = 'SELECT
            vehicles.id_vehicle as id,
            brands.name as brand,
            models.name as model,
            vehicles.status as status,
            vehicles.price,
            vehicles.year,
            vehicles.km,
            images.link as img,
            properties_meta.value as fuel
        FROM vehicles
        LEFT JOIN brands ON vehicles.brand = brands.id_brand
        INNER JOIN models ON vehicles.model = models.id_model
        INNER JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
        INNER JOIN vehicle_properties ON vehicles.id_vehicle = vehicle_properties.vehicle
        INNER JOIN properties_meta ON vehicle_properties.property = properties_meta.id_meta
        WHERE (properties_meta.name = "Carbourant") AND (status = 1 OR status = 2);
        ';
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
            echo 'Invalid URL or no data.';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
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
            $response = ['message' => 'Succès : Feedback inserted succesfully'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}


function addFeedback()
{
    $data = json_decode(file_get_contents('php://input'), true);
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
            $response = ['message' => 'Succès : Feedback inserted succesfully'];
        }
    } catch (PDOException $e) {
        $response = handleError($e);
    }
    echo json_encode($response);
}