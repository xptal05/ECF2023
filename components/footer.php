<?php

include_once "func.php";
$data = fetchData();
$webPageInfoData = $data[1];

$mappedAndOrderedData = mapAndOrderData($webPageInfoData);
$hoursItems = $mappedAndOrderedData['Hours'];
$addressItems = $mappedAndOrderedData['Address'];
$contactItems = $mappedAndOrderedData['Contact'];

?>


<footer class="footer">
    <div class="footer-section"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d90493.80973931908!2d-0.6684126763763204!3d44.8636881739578!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd5527e8f751ca81%3A0x796386037b397a89!2sBordeaux!5e0!3m2!1sen!2sfr!4v1694630076577!5m2!1sen!2sfr" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
    <div class="footer-section">
        <div class="address"><div>Adresse</div>
        <?php
        foreach ($addressItems as $item) {
            $day = $item['text'];
            echo '<div>' . $day . '</div>';
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
            $day = $item['text'];
            echo '<div>' . $day . '</div>';
        }
        ?>
    </div>
    <div class="footer-section">
        <div>Links</div>
        <div>Legal</div>
    </div>
</footer>