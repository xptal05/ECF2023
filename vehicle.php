<?php

$user = 'studi';
$password = 'studi-ecf';
$db = 'studi_ecf';
$host = 'localhost';
$port = 3001;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define your functions here
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
    // Handle connection error if needed
}

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <section id="notifications"></section>
    <?php include_once "./components/header.php";
    $vehicle_infos = vehicle_infos();

    $propertiesMain = ['Caroserie', 'Transmission', 'Carbourant', 'Conformité', 'Consomation', 'Portes', 'Couleur'];
    $propertiesMapping = ['Caroserie' => 'Caroserie', 'Transmission' => 'Transmission', 'Carbourant' => 'Carbourant', 'Conformité' => 'conformity', 'Consomation' => 'consumption', 'Portes' => 'Portes', 'Couleur' => 'Couleur'];

    $iconProperties = [
        '<svg xmlns="http://www.w3.org/2000/svg" width="51" height="39" viewBox="0 0 51 39" fill="none">
             <path d="M49.8034 11.1563H43.8398L42.1823 7.01251C40.479 2.75221 36.4139 0 31.8249 0H19.1745C14.5865 0 10.5204 2.75221 8.81608 7.01251L7.15857 11.1563H1.19595C0.417998 11.1563 -0.152765 11.8874 0.0364933 12.6414L0.63415 15.0321C0.766631 15.564 1.24476 15.9375 1.7936 15.9375H3.79277C2.45501 17.1059 1.59339 18.8043 1.59339 20.7188V25.5C1.59339 27.1057 2.20698 28.5551 3.18714 29.6767V35.0625C3.18714 36.8226 4.61455 38.25 6.37465 38.25H9.56215C11.3223 38.25 12.7497 36.8226 12.7497 35.0625V31.875H38.2497V35.0625C38.2497 36.8226 39.6771 38.25 41.4372 38.25H44.6247C46.3848 38.25 47.8122 36.8226 47.8122 35.0625V29.6767C48.7924 28.5561 49.406 27.1067 49.406 25.5V20.7188C49.406 18.8043 48.5443 17.1059 47.2076 15.9375H49.2067C49.7556 15.9375 50.2337 15.564 50.3662 15.0321L50.9638 12.6414C51.1521 11.8874 50.5813 11.1563 49.8034 11.1563ZM14.7349 9.38023C15.461 7.56534 17.2191 6.37501 19.1745 6.37501H31.8249C33.7802 6.37501 35.5383 7.56534 36.2645 9.38023L38.2497 14.3438H12.7497L14.7349 9.38023ZM9.56215 25.4801C7.64965 25.4801 6.37465 24.2091 6.37465 22.3026C6.37465 20.396 7.64965 19.125 9.56215 19.125C11.4747 19.125 14.3434 21.9848 14.3434 23.8913C14.3434 25.7979 11.4747 25.4801 9.56215 25.4801ZM41.4372 25.4801C39.5247 25.4801 36.6559 25.7979 36.6559 23.8913C36.6559 21.9848 39.5247 19.125 41.4372 19.125C43.3497 19.125 44.6247 20.396 44.6247 22.3026C44.6247 24.2091 43.3497 25.4801 41.4372 25.4801Z" fill="#E6AB13" />
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="55" height="46" viewBox="0 0 55 46" fill="none">
        <path d="M44.6435 2V15.0465M44.6435 15.0465H38.875V20.2219M44.6435 15.0465H47.3929M38.875 24.2652V33.1066C45.6361 31.8211 46.5304 43.0802 46.4764 43.9427C46.4225 44.8053 52.7929 42.3737 52.9997 35.6944V15.0465H47.3929M47.3929 15.0465V2" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M49.4143 19.0359H42.6754C40.6807 19.0359 40.5728 21.3001 42.6754 21.3001H49.4143C51.1933 21.3001 51.4089 19.0359 49.4143 19.0359Z" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M49.4143 28.7398H42.6754C40.6807 28.7398 40.5728 31.0041 42.6754 31.0041H49.4143C51.1933 31.0041 51.4089 28.7398 49.4143 28.7398Z" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M49.4143 23.8879H42.6754C40.6807 23.8878 40.5728 26.1521 42.6754 26.1521H49.4143C51.1933 26.1521 51.4089 23.8878 49.4143 23.8879Z" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M26.4975 2V15.1883M26.4975 15.1883H20.7617C20.7617 15.1883 20.7617 20.4696 20.7617 28.4164C20.7617 36.3632 35.3177 36.7013 35.3177 28.4164V23.5644M26.4975 15.1883H29.2031M35.3177 18.5135V15.1883H29.2031M29.2031 15.1883V2" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="24.3731" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="31.4903" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="27.9337" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="24.3731" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="31.4903" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="27.9337" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="24.4825" cy="29.872" r="0.808665" fill="#E6AB13"/>
        <circle cx="31.5977" cy="29.872" r="0.808665" fill="#E6AB13"/>
        <circle cx="28.0391" cy="29.872" r="0.808665" fill="#E6AB13"/>
        <path d="M7.73581 2V15.1883M7.73581 15.1883H2.00001C2.00001 15.1883 1.99999 20.4696 2.00001 28.4164C2.00002 36.3632 16.556 36.7013 16.556 28.4164V23.5644M7.73581 15.1883H10.4414M16.556 18.5135V15.1883H10.4414M10.4414 15.1883V2" stroke="#E6AB13" stroke-width="2.15644" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="5.6114" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="12.7286" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="9.17195" cy="20.168" r="0.808665" fill="#E6AB13"/>
        <circle cx="5.6114" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="12.7286" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="9.17195" cy="25.02" r="0.808665" fill="#E6AB13"/>
        <circle cx="5.72077" cy="29.872" r="0.808665" fill="#E6AB13"/>
        <circle cx="12.838" cy="29.872" r="0.808665" fill="#E6AB13"/>
        <circle cx="9.27741" cy="29.872" r="0.808665" fill="#E6AB13"/>
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46" fill="none">
        <path d="M35.4235 0L33.37 2.36161L37.2718 5.95536C37.3907 6.07434 37.553 6.23363 37.6825 6.36607L36.861 7.29018C35.546 8.60518 36.4896 10.3071 36.9637 10.7812L39.428 13.2455V32.8571C39.428 36.1429 38.1396 36.1429 37.7852 36.1429C37.4307 36.1429 36.1423 36.1429 36.1423 32.8571V19.7143C36.1423 15.5056 32.8566 13.1429 29.5709 13.1429V6.57143C29.5709 4.76 28.1753 3.28571 26.2852 3.28571H6.57087C4.60193 3.28571 3.28516 4.68125 3.28516 6.57143V46H29.5709V16.4286C29.5709 16.4286 32.8566 16.4286 32.8566 19.7143V32.8571C32.8566 39.4286 36.9299 39.4286 37.7852 39.4286C38.6909 39.4286 42.7137 39.4286 42.7137 32.8571V9.85714C42.7137 6.57143 41.774 5.884 40.6602 4.82589L35.4235 0ZM6.57087 9.85714H26.2852V19.7143H6.57087V9.85714Z" fill="#E6AB13"/>
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
        <path d="M45.873 28.6458C45.7897 28.5208 45.6855 28.4167 45.6439 28.3333C45.6022 28.1875 45.5814 28.0208 45.5605 27.875C45.4564 27.1875 45.3105 26.1667 44.498 25.3542C43.6855 24.5417 42.6647 24.375 41.9772 24.2708C41.8105 24.25 41.6439 24.2292 41.5397 24.2083C41.4147 24.1458 41.3105 24.0417 41.1855 23.9583C40.623 23.5417 39.7897 22.9167 38.6022 22.9167C37.4147 22.9167 36.5814 23.5417 36.0189 23.9583C35.8939 24.0417 35.7897 24.1458 35.7064 24.1875C35.5605 24.2292 35.3939 24.25 35.248 24.2708C34.5605 24.375 33.5397 24.5208 32.7272 25.3542C31.9147 26.1875 31.748 27.1875 31.6647 27.875C31.6439 28.0417 31.623 28.2083 31.6022 28.3125C31.5397 28.4375 31.4355 28.5417 31.3522 28.6667C30.9355 29.2292 30.3105 30.0625 30.3105 31.25C30.3105 32.4375 30.9355 33.2708 31.3522 33.8333C31.4355 33.9583 31.5397 34.0625 31.5814 34.1667C31.623 34.3125 31.6439 34.4792 31.6647 34.625C31.7689 35.3125 31.9147 36.3333 32.7272 37.1458C33.5397 37.9583 34.5605 38.125 35.248 38.2292C35.4147 38.25 35.5814 38.2708 35.6855 38.3125C35.8105 38.375 35.9147 38.4792 36.0397 38.5625C36.6022 38.9792 37.4355 39.6042 38.623 39.6042C39.8105 39.6042 40.6439 38.9792 41.2064 38.5625C41.3314 38.4792 41.4355 38.375 41.5397 38.3333C41.6855 38.2917 41.8522 38.2708 41.998 38.25C42.6855 38.1458 43.7064 38 44.5189 37.1667C45.3314 36.3542 45.498 35.3333 45.5814 34.6458C45.6022 34.4792 45.623 34.3333 45.6439 34.2083C45.7064 34.0833 45.8105 33.9792 45.8939 33.8542C46.3105 33.2917 46.9355 32.4583 46.9355 31.2708C46.9355 30.0833 46.2897 29.2083 45.873 28.6458ZM42.5397 31.3333C42.2897 31.6458 41.998 32.0625 41.7897 32.5625C41.5814 33.0833 41.498 33.5833 41.4355 34C41.4355 34.0208 41.4355 34.0625 41.4147 34.0833C41.3939 34.0833 41.3522 34.0833 41.3314 34.1042C40.9147 34.1667 40.4147 34.25 39.9147 34.4583C39.4147 34.6667 38.998 34.9792 38.6855 35.2083C38.6647 35.2292 38.623 35.25 38.6022 35.2708C38.5814 35.25 38.5397 35.2292 38.5189 35.2083C38.1855 34.9583 37.7897 34.6667 37.2897 34.4583C36.7689 34.25 36.2689 34.1667 35.8522 34.1042C35.8314 34.1042 35.7897 34.1042 35.7689 34.0833C35.7689 34.0625 35.7689 34.0208 35.748 34C35.6855 33.5833 35.6022 33.0833 35.3939 32.5625C35.1855 32.0625 34.873 31.6458 34.6439 31.3333C34.623 31.3125 34.6022 31.2708 34.5814 31.25C34.6022 31.2292 34.623 31.1875 34.6439 31.1667C34.8939 30.8542 35.1855 30.4375 35.3939 29.9375C35.6022 29.4167 35.6855 28.9167 35.748 28.5C35.748 28.4792 35.748 28.4375 35.7689 28.4167C35.7897 28.4167 35.8314 28.4167 35.8522 28.3958C36.248 28.3333 36.7689 28.25 37.2689 28.0417C37.7689 27.8333 38.1855 27.5208 38.498 27.2917C38.5189 27.2708 38.5605 27.25 38.5814 27.2292C38.6022 27.25 38.6439 27.2708 38.6647 27.2917C38.9772 27.5417 39.3939 27.8333 39.8939 28.0417C40.4147 28.25 40.9147 28.3333 41.3105 28.3958C41.3314 28.3958 41.373 28.3958 41.3939 28.4167C41.3939 28.4375 41.3939 28.4792 41.4147 28.5C41.4772 28.8958 41.5605 29.4167 41.7689 29.9375C41.9772 30.4375 42.2689 30.8333 42.5189 31.1667C42.5397 31.1875 42.5605 31.2292 42.5814 31.25C42.6022 31.2708 42.5605 31.3125 42.5397 31.3333Z" fill="#E6AB13"/>
        <path d="M39.1471 30.2917L38.6055 29.1667L38.043 30.2917L36.543 30.5208L37.7096 31.3333L37.2305 32.5833L38.6055 32L39.9596 32.5833L39.5013 31.3333L40.6471 30.5208L39.1471 30.2917Z" fill="#E6AB13"/>
        <path d="M43.7493 41.6667H8.33268V8.33333H31.2493V18.75C31.2493 19.8958 32.1868 20.8333 33.3327 20.8333H43.7493C43.8535 20.8333 43.9368 20.7917 44.041 20.7708C44.1868 20.75 44.3327 20.7292 44.4577 20.6875C44.6035 20.6458 44.7077 20.5625 44.8327 20.4792C44.916 20.4375 44.9993 20.4167 45.0827 20.3542C45.1035 20.3333 45.1243 20.2917 45.1452 20.2708C45.2493 20.1667 45.3327 20.0625 45.416 19.9375C45.4993 19.8333 45.5827 19.7292 45.6243 19.625C45.666 19.5208 45.6868 19.3958 45.7285 19.2708C45.7702 19.125 45.8118 18.9792 45.8118 18.8333C45.8118 18.8125 45.8327 18.7917 45.8327 18.75C45.8327 18.6458 45.791 18.5625 45.7702 18.4792C45.7493 18.3333 45.7285 18.1875 45.6868 18.0417C45.6452 17.8958 45.5618 17.7917 45.4785 17.6667C45.4368 17.5833 45.416 17.5 45.3327 17.4167L34.916 4.91666C34.8952 4.89583 34.8743 4.875 34.8535 4.85416C34.7077 4.70833 34.541 4.58333 34.3743 4.47916C34.3118 4.4375 34.2493 4.375 34.166 4.35416C33.916 4.22916 33.6452 4.16666 33.3327 4.16666H6.24935C5.10352 4.16666 4.16602 5.10416 4.16602 6.25V43.75C4.16602 44.8958 5.10352 45.8333 6.24935 45.8333H43.7493C44.8952 45.8333 45.8327 44.8958 45.8327 43.75C45.8327 42.6042 44.8952 41.6667 43.7493 41.6667ZM35.416 12L39.291 16.6667H35.416V12Z" fill="#E6AB13"/>
        <path d="M12.4993 20.8333H18.7493C19.8952 20.8333 20.8327 19.8958 20.8327 18.75C20.8327 17.6042 19.8952 16.6667 18.7493 16.6667H12.4993C11.3535 16.6667 10.416 17.6042 10.416 18.75C10.416 19.8958 11.3535 20.8333 12.4993 20.8333Z" fill="#E6AB13"/>
        <path d="M27.0827 22.9167H12.4993C11.3535 22.9167 10.416 23.8542 10.416 25C10.416 26.1458 11.3535 27.0833 12.4993 27.0833H27.0827C28.2285 27.0833 29.166 26.1458 29.166 25C29.166 23.8542 28.2285 22.9167 27.0827 22.9167Z" fill="#E6AB13"/>
        <path d="M12.4993 33.3333H22.916C24.0618 33.3333 24.9993 32.3958 24.9993 31.25C24.9993 30.1042 24.0618 29.1667 22.916 29.1667H12.4993C11.3535 29.1667 10.416 30.1042 10.416 31.25C10.416 32.3958 11.3535 33.3333 12.4993 33.3333Z" fill="#E6AB13"/>
        <path d="M12.4993 39.5833H27.0827C28.2285 39.5833 29.166 38.6458 29.166 37.5C29.166 36.3542 28.2285 35.4167 27.0827 35.4167H12.4993C11.3535 35.4167 10.416 36.3542 10.416 37.5C10.416 38.6458 11.3535 39.5833 12.4993 39.5833Z" fill="#E6AB13"/>
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
        <path d="M47.9375 25C47.9375 37.668 37.668 47.9375 25 47.9375C12.332 47.9375 2.0625 37.668 2.0625 25C2.0625 12.332 12.332 2.0625 25 2.0625C37.668 2.0625 47.9375 12.332 47.9375 25Z" stroke="#E6AB13"/>
        <path d="M26.3281 22.625C26.188 22.3989 25.9925 22.2123 25.76 22.0829C25.5276 21.9535 25.266 21.8856 25 21.8856C24.734 21.8856 24.4724 21.9535 24.24 22.0829C24.0075 22.2123 23.812 22.3989 23.6719 22.625C22.6875 24.1875 19.5312 29.625 19.5312 32C19.5313 33.4504 20.1074 34.8414 21.133 35.867C22.1586 36.8926 23.5496 37.4688 25 37.4688C26.4504 37.4688 27.8414 36.8926 28.867 35.867C29.8926 34.8414 30.4688 33.4504 30.4688 32C30.4688 29.6875 27.3125 24.2188 26.3281 22.625Z" fill="#E6AB13"/>
        <path d="M25 1.5625C18.784 1.5625 12.8226 4.0318 8.42719 8.42719C4.0318 12.8226 1.5625 18.784 1.5625 25C1.5625 25.5312 1.5625 26.0469 1.64062 26.5625H12.5781C12.3617 24.8053 12.521 23.0221 13.0456 21.3311C13.5701 19.64 14.4479 18.0797 15.6209 16.7535C16.7938 15.4272 18.2351 14.3652 19.8493 13.6379C21.4635 12.9106 23.2139 12.5344 24.9844 12.5344C26.7549 12.5344 28.5052 12.9106 30.1194 13.6379C31.7337 14.3652 33.175 15.4272 34.3479 16.7535C35.5208 18.0797 36.3986 19.64 36.9232 21.3311C37.4477 23.0221 37.6071 24.8053 37.3906 26.5625H48.3281C48.3281 26.0469 48.4062 25.5312 48.4062 25C48.4063 18.7894 45.9412 12.8327 41.5526 8.43822C37.164 4.04374 31.2106 1.57078 25 1.5625Z" fill="#E6AB13"/>
        <path d="M25 1.5625C24.4688 1.5625 23.9531 1.5625 23.4375 1.64062V12.5781C24.4741 12.4323 25.5259 12.4323 26.5625 12.5781V1.64062C26.0469 1.5625 25.5312 1.5625 25 1.5625Z" fill="black"/>
        <path d="M6 11.3281L13.9219 19.25C14.4133 18.3108 15.0166 17.4347 15.7188 16.6406L8 8.90625C7.28231 9.67002 6.61428 10.479 6 11.3281Z" fill="black"/>
        <path d="M42 8.90625L34.2812 16.7188C34.9834 17.5128 35.5867 18.389 36.0781 19.3281L44 11.4063C43.3884 10.5303 42.7204 9.69517 42 8.90625Z" fill="#0A0A0A"/>
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="38" height="42" viewBox="0 0 38 42" fill="none">
        <path d="M33.5835 0.166641H19.8544C18.7511 0.171293 17.6947 0.613333 16.9169 1.39581L0.854369 17.4375C0.661282 17.6321 0.508521 17.863 0.404845 18.1168C0.301169 18.3707 0.248617 18.6425 0.250202 18.9166V27.5208C0.240251 28.5319 0.598282 29.512 1.25756 30.2786C1.91684 31.0452 2.83238 31.546 3.83353 31.6875L8.1252 32.3125C10.212 32.616 12.1616 33.5325 13.7269 34.9456C15.2921 36.3588 16.4024 38.2049 16.9169 40.25C17.0309 40.7122 17.2999 41.1214 17.6789 41.4095C18.058 41.6976 18.5243 41.8472 19.0002 41.8333H33.5835C34.6886 41.8333 35.7484 41.3943 36.5298 40.6129C37.3112 39.8315 37.7502 38.7717 37.7502 37.6666V4.33331C37.7502 3.22824 37.3112 2.16843 36.5298 1.38703C35.7484 0.605628 34.6886 0.166641 33.5835 0.166641ZM33.5835 16.8333H7.35437L19.8544 4.33331H33.5835V16.8333Z" fill="#E6AB13"/>
        </svg>',
        '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
        <circle cx="20" cy="20" r="18.5" stroke="#E6AB13" stroke-width="3" fill=""/>
        </svg>'
    ];

    $vehicle_gallery = vehicle_gallery();
    ?>
    <section id="popup"></section>
    <div class="vehicle-page">
        <div class="back-btn">
            <a   href="javascript:history.back()"><- REVENIR A LA LISTE</a>
        </div>
        <div class="vehicle-container-main">
            <div class="vehicle-container-imgs">
                <div class="vehicle-img-main"><img <?php echo 'src="' .str_replace("../", "./", $vehicle_infos['img']) . '"' ?>></div>
                <div class="vehicle-img-gallery">
                    <?php
                    if (!empty($vehicle_gallery)) {
                        foreach ($vehicle_gallery as $image) {
                            $imageLink = $image[0];
                            echo '<img class="gallery-img" src="' . str_replace("../", "./",$imageLink) . '" alt="Vehicle Image">';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="vehicle-container-infos">
                <h1 class="uppercase primary"><?php echo $vehicle_infos['brandname'] . ' ' . $vehicle_infos['modelname']; ?></h1>
                <div>
                    <h3><?php echo $vehicle_infos['year'] . ', ' . $vehicle_infos['km'] . ' km, ' ?></h3>
                    <h2><?php echo $vehicle_infos['price'] . ' EUR'; ?></h2>
                </div>
                <div class="vehicle-properties-main">
                    <?php
                    for ($i = 0; $i < sizeof($propertiesMain); $i++) {
                        echo ' <div class="vehicle-properties-main-container">        
                                <div class="property_icon">';
                        if ($propertiesMain[$i] == "Couleur") {
                            $fill = explode('/', $vehicle_infos[$propertiesMapping[$propertiesMain[$i]]])[1];
                            echo '<div class="colour-cirle" style="width:50px; height:50px; border:solid 2px var(--primary-color); border-radius:50px;background:' . $fill . '"></div>';
                        } else {
                            echo $iconProperties[$i];
                        }
                        echo '</div>
                        <div class="property-text">
                            <div>' . $propertiesMain[$i] . '</div>
                            <div>' . explode('/', $vehicle_infos[$propertiesMapping[$propertiesMain[$i]]])[0] . '</div>
                        </div>
                    </div>';
                    } ?>
                </div>
                <a class="btn uppercase action-btn" href="?vehicle=<?php echo $vehicle_infos['brand'] . '-' . $vehicle_infos['model'] . '-' . $vehicle_infos['year'] ?>">contactez nous</a>
            </div>
        </div>
        <div class="vehicle-container-additional-info">
            <div class="vehicle-equipment-container">
                <h2>Equipments et options</h2>
                <p><?php echo $vehicle_infos['Options'] ?></p>
            </div>
            <div class="vehicle-remarques-container">
                <h2>Remarques</h2>
                <p><?php echo $vehicle_infos['other_equipment'] ?></p>
            </div>
        </div>
        <?php include_once "./components/footer.php" ?>
        <script src="script.js"></script>
        <script src="script-contact.js"></script>
        <script>
            const actionBtns = document.querySelectorAll('.action-btn');
            const popup = document.getElementById('popup')

            actionBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault()
                    popup.innerHTML = `<div class="popup-div"><div class="form-close-btn">X</div><?php include 'contact-form.php' ?></div>`
                    messageEvent()
                    // Use setTimeout to ensure that the element is available for modification
                    setTimeout(function() {
                        const subject = document.getElementById('subject');
                        subject.value = "Enquiry for" + btn.getAttribute("href").replace("?vehicle=", " ");
                        subject.readOnly = true
                        console.log(subject);

                        const closeBtn = document.querySelector('.form-close-btn')
                        closeBtn.addEventListener('click', (e) => {
                            popup.innerHTML = ``
                        })
                    }, 0);
                })

            })
        </script>
</body>

</html>