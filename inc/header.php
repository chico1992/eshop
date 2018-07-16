<?php
    require_once("init.php");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Cedric">
        <link rel="icon" href="../../../../favicon.ico">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

        <title>My Eshop.com | Best deal$ online</title>

        <!-- Bootstrap core CSS -->
        <link href="assets/bootstrap-4/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="<?= URL?>">MyEshop.com</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL?>">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL?>eshop.php">Eshop</a>
                    </li>
                    <?php if(!userConnect()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Connect</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="<?= URL?>login.php">Login</a>
                            <a class="dropdown-item" href="<?= URL?>signup.php">Sign Up</a>
                        </div>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL?>profile.php">Profile</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <?php if(userAdmin()): ?>
                    
                    <li class="nav-item">
                        <form class="form-inline my-2 my-lg-0">
                            <a class="nav-link" href="<?= URL?>admin/product_form.php">Backoffice</a>
                        </form>
                    </li>
                        
                    <?php endif; ?>
                    <li>
                        <a class="nav-link" href="<?=URL?>cart.php"><i class="fas fa-shopping-cart"></i><?php if(productNumber()){echo'<span class="bubble">' . productNumber() . '</span>';} ?></a>
                    </li>
                </ul>
            </div>
            <?php if(userConnect()): ?>
                <a  href="profile.php"><img  style='width:40px; border:2px solid #2FFA21; border-radius:50%;' src='<?= URL."uploads/user/".$_SESSION['user']['picture']?>'></a>
                <a class="nav-link" href="<?= URL?>logout.php">Logout</a>
            <?php endif; ?>
        </nav>


        <main role="main" class="container">
            <div class="starter-template">