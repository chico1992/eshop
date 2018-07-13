<?php
    require_once("inc/header.php");

    $page= "My Profile";


    if(!userConnect()){
        header('location:login.php');
        exit();
    }
?>


    <h1><?= $page ?></h1>
    <p>Please find your information below:</p>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Firstname: <?= $_SESSION['user']['firstname']?></li>
        <li class="list-group-item">Lastname: <?= $_SESSION['user']['lastname']?></li>
        <li class="list-group-item">Email: <?= $_SESSION['user']['email']?></li>
        
    </ul>
<?php
    require_once("inc/footer.php");
?>