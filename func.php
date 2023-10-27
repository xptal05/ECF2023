<?php

require_once "./back/config/db.php";
$pdo = connectToDatabase($dbConfig);


function fetch_vehicle_to_page()
{
    global $pdo;
    $vehicle = $_GET['vehicle'] ?? 1;
    try {
        $statement = $pdo->prepare(
            'SELECT vehicle_properties.vehicle, properties_meta.name, properties_meta.value
            FROM vehicle_properties
            LEFT JOIN properties_meta ON vehicle_properties.property = properties_meta.id_meta
            WHERE vehicle_properties.vehicle = :id'
        );
        $statement->bindValue('id', $vehicle, PDO::PARAM_INT);
        if ($statement->execute()) {
            $vehicle_info = $statement->fetch(PDO::FETCH_ASSOC);
            return $vehicle_info ;
        } else {
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
        echo 'exception';
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

function fetchData()
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

//WEB PAGE INFO
function mapAndOrderData($data)
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