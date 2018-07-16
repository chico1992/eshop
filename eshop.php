<?php
    // guillaume's code on sharemycode.fr/esh
    require_once("inc/header.php");
    $page = "eshop";

    $result_cat = $pdo->query('SELECT DISTINCT category FROM product');


    $content .="<ul class='list-group'>";
    $content .= "<li class='list-group-item'><a href='eshop.php'>ALL</a></li>";
    while($category = $result_cat->fetch()){
        
        $content .= "<li class='list-group-item'><a href='?cat=$category[category]'>$category[category]</a></li>";
    }
    $content .= "</ul>";

    if($_GET){
        if(isset($_GET['cat'])){
            $req="SELECT * FROM product WHERE category = :category";
            $result = $pdo->prepare($req);
            $result->bindValue(':category',$_GET['cat'],PDO::PARAM_STR);
        }
    }else{
        $req = "SELECT * FROM product";
        $result = $pdo->prepare($req);
    }
    $result->execute();
    $products=$result->fetchAll();
    $product_view = "";
    foreach ($products as $product) {
        $product_view .= "<div class='col-sm-4'style='margin-bottom:20px;'>";
        $product_view .=  "<div class='card style='max-height:700px;min-height:700px;' >";
        $product_view .= "<img class='card-img-top' style='max-height:300px;min-height:300px;' src='".URL."uploads/product/".$product['picture']."' >";
        $product_view .= "<div class='card-body' style='background:#FFEBCD;'>";
        $product_view .= "<h5 class='card-title'>$product[title]</h5>";
        $product_view .= "<p class='card-text'>".substr($product['description'],0,40)."</p>";
        $product_view .= "<a href='product_page.php?id=$product[id_product]' class='btn btn-info' style='color:;'>Go to product</a>";
        $product_view .= "</div>";
        $product_view .= "</div>";
        $product_view .= "</div>";
    }

?>

    <h1><?= $page ?></h1>
    <p class="lead">Please feel free to buy a lot of stuff and spend all of your money Ki$$e$ !</p>
    <div class="row">
        <div class="col-md-2">
            <?= $content ?>
        </div>
        <div class="col-md-10">
            <div class="container"> 
                <div class="row">
                    <?= $product_view ?>                    
                </div>
            </div>
        </div>
    </div>


<?php
    require_once("inc/footer.php")

?>