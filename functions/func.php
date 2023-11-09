<?php

include_once "./config/db.php";
$pdo = connectToDatabase($dbConfig);


function vehicle_infos()
{
    global $pdo;
    $vehicleID = $_GET['vehicle'] ?? 1;
    $sql = 'SELECT
    brands.name AS brandname,
    models.name AS modelname,
    vehicles.*,
    images.link AS img,
    images.id_img AS id_img,
    IFNULL((SELECT properties_meta.value FROM properties_meta
             LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
             WHERE properties_meta.name = "Carbourant"
               AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Carbourant,
    IFNULL((SELECT properties_meta.value FROM properties_meta
             LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
             WHERE properties_meta.name = "Caroserie"
               AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Caroserie,
    IFNULL((SELECT properties_meta.value FROM properties_meta
             LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
             WHERE properties_meta.name = "Transmission"
               AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Transmission,
    IFNULL((SELECT properties_meta.value FROM properties_meta
             LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
             WHERE properties_meta.name = "Couleur"
               AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Couleur,
    IFNULL((SELECT properties_meta.value FROM properties_meta
             LEFT JOIN vehicle_properties ON properties_meta.id_meta = vehicle_properties.property
             WHERE properties_meta.name = "Portes"
               AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Portes,
    IFNULL((SELECT vehicle_properties.property FROM vehicle_properties
             WHERE vehicle_properties.property_name = "Options" AND vehicle_properties.vehicle = vehicles.id_vehicle), "Unknown") AS Options,
    IFNULL((SELECT GROUP_CONCAT(
                    DISTINCT images.link
                    ORDER BY images.link
                    SEPARATOR ", "
                ) FROM images 
            WHERE images.associated_to_vehicle = vehicles.id_vehicle AND images.type = 1), "No Images") AS gallery,
    IFNULL((SELECT GROUP_CONCAT(
                    DISTINCT images.id_img
                    ORDER BY images.id_img
                    SEPARATOR ", "
                ) FROM images 
            WHERE images.associated_to_vehicle = vehicles.id_vehicle AND images.type = 1), "No Images") AS gallery_ids       
    FROM vehicles
    LEFT JOIN brands ON vehicles.brand = brands.id_brand
    LEFT JOIN models ON vehicles.model = models.id_model
    LEFT JOIN images ON vehicles.id_vehicle = images.associated_to_vehicle AND images.type = 2
    WHERE vehicles.id_vehicle = ?';
    $statement = $pdo->prepare($sql);
    $statement->bindParam(1, $vehicleID, PDO::PARAM_INT);
    if ($statement->execute()) {
        $vehicle_info = $statement->fetch(PDO::FETCH_ASSOC);
        return $vehicle_info;
    } else {
        echo 'Error executing query';
    }
}

function vehicle_gallery()
{
    global $pdo;
    $vehicleID = $_GET['vehicle'] ?? 1;
    $sql = "SELECT images.link
    FROM images 
    WHERE images.type = 1
    AND associated_to_vehicle = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(1, $vehicleID, PDO::PARAM_INT);
    if ($statement->execute()) {
        $vehicle_g_img = $statement->fetchAll(PDO::FETCH_NUM);
        return $vehicle_g_img;
    } else {
        echo 'Error executing query';
    }
}

function fetch_filters_to_page($filter)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM $filter";
        $statement = $pdo->prepare($sql);
        if ($statement->execute()) {
            while ($filter_value = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo '<input type="checkbox" name="brand" value="' . $filter_value['name'] . '">' . $filter_value['name'];
            }
        } else {
            echo 'Une erreur est survenue';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
}

function fetch_min_max_values_filters()
{
    try {

        global $pdo;
        $statement = $pdo->prepare('SELECT MIN(km), MAX(km), MIN(year), MAX(year), MIN(price), MAX(price)
        FROM vehicles
        WHERE status = 1 OR status = 2');
        if ($statement->execute()) {
            $filter_minmax = $statement->fetch(PDO::FETCH_ASSOC);
            return $filter_minmax;
        } else {
            echo 'Une erreur est survenue';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
    }
}

function fetchData()        //DASHBORD
{
    global $pdo;

    try {
        $sql1 = 'SELECT * FROM feedbacks';
        $sql2 = 'SELECT * FROM web_page_info';
        $sql3 = 'SELECT * FROM images WHERE type = 3';

        $data1 = [];
        $data2 = [];
        $data3 = [];

        if (!empty($sql1)) {
            $statement1 = $pdo->prepare($sql1);
            if ($statement1->execute()) {
                $data1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo 'An error occurred while fetching data from feedbacks.';
            }
        }

        if (!empty($sql2)) {
            $statement2 = $pdo->prepare($sql2);
            if ($statement2->execute()) {
                $data2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo 'An error occurred while fetching data from web_page_info.';
            }
        }

        if (!empty($sql3)) {
            $statement3 = $pdo->prepare($sql3);
            if ($statement3->execute()) {
                $data3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo 'An error occurred while fetching data from web_page_info.';
            }
        }

        return [$data1, $data2, $data3];
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'Exception';
    }
}

function mapAndOrderData($data)     //WEB PAGE INFO
{
    $mapping = [
        '1' => 'Service',
        '2' => 'Address',
        '3' => 'Contact',
        '4' => 'Hours',
        '5' => 'About',
        '7' => 'Reasons'
    ];
    $mappedData = [];

    foreach ($data as $item) {
        $type = $item['type'];
        if (isset($mapping[$type])) {
            $category = $mapping[$type];
            $mappedData[$category][] = $item;
        }
    }

    foreach ($mappedData as &$categoryData) {
        usort($categoryData, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
    }

    return $mappedData;
}

