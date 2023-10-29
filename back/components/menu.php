<?php
include "func.php";
$jsonData = json_encode($_SESSION["user_id"]);
echo "<script>var UserId = {$jsonData};</script>";

if (isset($_POST["logout"])) {
    logout();
}
?>
<div class="menu">
    <img class="logo" src="src/Logo-back.svg">
    <div class="menu-section one">
        <a href="./">Tableau de bord</a>
        <a href="./messages.php">Messages</a>
        <a href="./feedback.php">Témoignages</a>
        <a href="./vehicles.php">Vehicules</a>
    </div>
    <?php admin_menu(); ?>
    <div class="menu-user-name"><div><?php echo $_SESSION['user_name'].' '. $_SESSION["admin"] = 1 ? 'Admin' : '' ?></div><button type="submit" form="logoutForm" class="logoutBtn">
<img src="./src/right-from-bracket.svg" style="width:30px"></button></div>

</div>
<form id="logoutForm" action="" method="post">
    <input type="hidden" name="logout" value="1">
</form>