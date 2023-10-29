<?php
include_once "func.php";
$data = fetchData();
$feedbacksData = $data[0];
$webPageInfoData = $data[1];
$icons = $data[2];

$mappedAndOrderedData = mapAndOrderData($webPageInfoData);
$contactItems = $mappedAndOrderedData['Contact'];

$phoneNumber = ''; // Initialize the variable

foreach ($contactItems as $item) {
    if ($item['category'] === 'phone') {
        $phoneNumber = $item['text'];
        break; // Exit the loop once the item is found
    }
}

$formatedPhone = implode(' ', str_split(str_replace("+33", "0", $phoneNumber), 2));

?>
<div class="header">
    <a href="index.php"><img src="./src/logo.svg" class="logo-header"></a>
    <div class="menu">
        <a href="index.php">acceuil</a>
        <a href="catalogue.php">catalogue</a>
        <a href="index.php#feedback">temoignages</a>
        <a href="index.php#contact">contact</a>
        <a href="tel:<?php echo $phoneNumber ?>" class="primary telephone">
            <i class="fa-solid fa-phone-volume"></i>
            <span><?php echo $formatedPhone ?></span>
        </a>
    </div>
    <div class="burger-menu">
        <div class="burger-lines">
            <div class="burger-line top"></div>
            <div class="burger-line middle"></div>
            <div class="burger-line bottom"></div>
        </div>
        <div class="burger-menu-container">
            <a href="index.php">acceuil</a>
            <a href="catalogue.php">catalogue</a>
            <a href="index.php#feedback">temoignages</a>
            <a href="index.php#contact">contact</a>
            <a href="tel:<?php echo $phoneNumber ?>" class="telephone">
                <i class="fa-solid fa-phone-volume"></i>
                <span><?php echo $formatedPhone ?></span>
            </a>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/d27983d062.js" crossorigin="anonymous"></script>