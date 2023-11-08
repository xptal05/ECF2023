<?php

include_once "func.php";
$data = fetchData();
$webPageInfoData = $data[1];

$mappedAndOrderedData = mapAndOrderData($webPageInfoData);
$hoursItems = $mappedAndOrderedData['Hours'];
$addressItems = $mappedAndOrderedData['Address'];
$contactItems = $mappedAndOrderedData['Contact'];

//Default Values
$latitude = "45.9883317624201";
$longitude = "-2.702384256177646";
$address = "Lyon";

//33%20Rue%20Smith%2C%2069002%20Lyon

//GET THE VALUES FROM DB FOR IFRAME GOOGLE MAPS
$addressParts = [
    'street' => '',
    'postal_code' => '',
    'city' => ''
];

foreach ($addressItems as $item) {
    if ($item['category'] === 'latitude') {
        $latitude = $item['text'];
    } elseif ($item['category'] === 'longtitude') {
        $longitude = $item['text'];
    } elseif (array_key_exists($item['category'], $addressParts)) {
        $addressParts[$item['category']] = $item['text'];
    }
}
$address = str_replace(' ', '%20', implode('%20', $addressParts));


?>


<footer class="footer">
    <div class="footer-section">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d<?php echo $latitude + 4000 ?>!2d<?php echo $longitude ?>!3d<?php echo $latitude ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x123456789abcdef01%3A0x9876543210fedcba!2s<?php echo $address ?>!5e0!3m2!1sen!2sfr!4v1699001772720!5m2!1sen!2sfr" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="footer-section">
        <div class="address">
            <div>Adresse</div>
            <?php
            foreach ($addressItems as $item) {
                if (array_key_exists($item['category'], $addressParts)) {
                    $address = $item['text'];
                    echo '<div>' . $address . '</div>';
                }
            }
            ?>
        </div>
        <div class="hours">
            <div>Horaires</div>
            <?php
            foreach ($hoursItems as $item) {
                $day = $item['text'];
                echo '<div>' . $day . '</div>';
            }

            ?>
        </div>
    </div>
    <div class="footer-section">Contact
        <?php
        foreach ($contactItems as $item) {
            $contact = $item['text'];
            //format phone number
            if ($item['category'] === 'phone') {
                $contact = implode(' ', str_split(str_replace("+33", "0", $item['text']), 2));
            }
            echo '<div>' . $contact . '</div>';
        }



        ?>
    </div>
    <div class="footer-section">
        <div>Links</div>
        <div>Legal</div>
    </div>
</footer>