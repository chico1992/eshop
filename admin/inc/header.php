<?php
    require_once('../inc/init.php');

    if(isset($_GET['a']) && $_GET['a'] == 'logout'){
        unset($_SESSION['user']);
    }
    if(!userAdmin()){
        header('location:'.URL.'index.php');
        exit();
    }

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Backoffice</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-4/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= URL?>">MyEshop.com</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="?a=logout">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="product_form.php">
                  <span data-feather="file"></span>
                  Add a product
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="product_list.php">
                  <span data-feather="file"></span>
                  Show products
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="user_list.php">
                  <span data-feather="file"></span>
                  Users List
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="order_details.php">
                  <span data-feather="file"></span>
                  Order Details
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">