<?php

?>
<div class="header">
    <a href="index.php"><img src="../src/logo.svg" class="logo-header"></a>
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
            <a href="tel:<?php echo $phoneNumber ?>" class="primary telephone">
                <i class="fa-solid fa-phone-volume"></i>
                <span><?php echo $formatedPhone ?></span>
            </a>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/d27983d062.js" crossorigin="anonymous"></script>