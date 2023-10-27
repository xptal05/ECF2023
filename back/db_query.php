<?php
require_once "./config/db.php";

$pdo = connectToDatabase($dbConfig);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'fetchData') {
        fetchData();
    }
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
