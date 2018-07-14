<?php

    require_once("inc/header.php");

    
    
    if($_POST){
        foreach ($_POST as $key => $value) {
            $_POST[$key] = addslashes($value);
        }
        
        if(!empty($_FILES['product_picture']['name'])){   // check if an image has been uploaded
            // I give a random name to my picture
            $picture_name =  $_POST['title'] . $_POST['reference'] . '_' . time() . '-' . rand(1,999) .'_' . $_FILES['product_picture']['name'];
            
            $picture_name = str_replace(' ','-',$picture_name);
            
            // we register the path of my file
            $picture_path = ROOT_TREE . 'uploads/product/'.$picture_name;
            
            if($_FILES['product_picture']['size']>2000000){
                $msg_error .= "<div class='alert alert-danger' role='alert'>Please select a 2Mo file maximum! </div>";
            }
            
            $type_picture = ['image/jpeg', 'image/png', 'image/gif'];
            if(!in_array($_FILES['product_picture']['type'],$type_picture)){
                $msg_error .= "<div class='alert alert-danger' role='alert'>Please use JPEG/JPG, PNG or GIF format</div>";
            }

            if(isset($_POST['actual_picture']) && file_exists(ROOT_TREE . 'uploads/product/'.$_POST['actual_picture']) && ($_POST['actual_picture']!= 'default.jpg')){
                unlink(ROOT_TREE . 'uploads/product/'.$_POST['actual_picture']);
            }

        }elseif(isset($_POST['actual_picture'])){
            $picture_name = $_POST['actual_picture'];
        }else{
            $picture_name = 'default.jpg';
        }
        
        //other check possible here
        if(empty($msg_error)){

            if(!empty($_POST['id_product'])){
                $result = $pdo->prepare("UPDATE product SET reference=:reference, category=:category, title=:title, description=:description, color=:color, size=:size, gender=:gender, picture=:picture, picture2=NULL, price=:price, stock=:stock WHERE id_product = :id_product");

                $result->bindValue(':id_product',$_POST['id_product'],PDO::PARAM_INT);

            }else{
                $result = $pdo->prepare("INSERT INTO product (reference, category, title, description, color, size, gender, picture, picture2, price, stock) VALUES (:reference, :category, :title, :description, :color, :size, :gender, :picture, NULL , :price, :stock)");
            }




            
            $result->bindValue(':reference',$_POST['reference'],PDO::PARAM_STR);
            $result->bindValue(':category',$_POST['category'],PDO::PARAM_STR);
            $result->bindValue(':title',$_POST['title'],PDO::PARAM_STR);
            $result->bindValue(':description',$_POST['description'],PDO::PARAM_STR);
            $result->bindValue(':color',$_POST['color'],PDO::PARAM_STR);
            $result->bindValue(':size',$_POST['size'],PDO::PARAM_STR);
            $result->bindValue(':gender',$_POST['gender'],PDO::PARAM_STR);
            $result->bindValue(':picture',$picture_name,PDO::PARAM_STR);
            $result->bindValue(':price',$_POST['price'],PDO::PARAM_STR);
            $result->bindValue(':stock',$_POST['stock'],PDO::PARAM_INT);
            
            if($result->execute()){
                if(!empty($_FILES['product_picture']['name'])){
                    copy($_FILES['product_picture']['tmp_name'],$picture_path);
                }

                if(!empty($_POST['id_product'])){
                    header('location:product_list.php?update=1');
                }
            }
            
        }
    }

    if($_GET){
        if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
            $req = "SELECT * FROM product WHERE id_product = :id_product";
            $result = $pdo->prepare($req);
            $result->bindValue(':id_product',$_GET['id'],PDO::PARAM_INT);
            $result->execute();
            if($result->rowCount()== 1){
                $update_product = $result->fetch();

            }
        }
        // debug($update_product);
        
    }

    $reference = isset($update_product) ? $update_product['reference'] : '';
    $category = isset($update_product) ? $update_product['category'] : '';
    $title = isset($update_product) ? $update_product['title'] : '';
    $description = isset($update_product) ? $update_product['description'] : '';
    $color = isset($update_product) ? $update_product['color'] : '';
    $size = isset($update_product) ? $update_product['size'] : '';
    $gender = isset($update_product) ? $update_product['gender'] : '';
    $picture = isset($update_product) ? $update_product['picture'] : '';
    $price = isset($update_product) ? $update_product['price'] : '';
    $stock = isset($update_product) ? $update_product['stock'] : '';
    $id_product = isset($update_product) ? $update_product['id_product'] : '';
    $action = isset($update_product) ? 'Update' : 'Add';
    // debug($_POST);
    // debug($_FILES);
    
    ?>



    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $action?> a product</h1>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <?= $msg_error ?>
        <input type="hidden" name="id_product" value="<?= $id_product?>">
        <div class="form-group">
            <input type="text" class="form-control" name="reference" placeholder="Reference of the product..." required value="<?= $reference?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="category" placeholder="Category of the product..." required value="<?= $category?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="title" placeholder="Title of the product..." required value="<?= $title?>">
        </div>
        <div class="form-group">
            <textarea class="form-control" name="description" placeholder="Description of the product..." required ><?= $reference?></textarea>
        </div>
        <div class="form-group"> 
            <select name="color" class="form-control" required>
                <option disabled selected>Color of the product...</option>
                <option <?= ($color == "black")? "selected" : '' ?> >black</option>
                <option <?= ($color == "red")? "selected" : '' ?>>red</option>
                <option <?= ($color == "green")? "selected" : '' ?>>green</option>
                <option <?= ($color == "white")? "selected" : '' ?>>white</option>
                <option <?= ($color == "pink")? "selected" : '' ?>>pink</option>
                <option <?= ($color == "blue")? "selected" : '' ?>>blue</option>
                <option <?= ($color == "indigo")? "selected" : '' ?>>indigo</option>
                <option <?= ($color == "orange")? "selected" : '' ?>>orange</option>
                <option <?= ($color == "yellow")? "selected" : '' ?>>yellow</option>
                <option <?= ($color == "purple")? "selected" : '' ?>>purple</option>
            </select>
        </div>
        <div class="form-group"> 
            <select name="size" class="form-control" required>
                <option disabled selected>Size of the product...</option>
                <option <?= ($size == "xs")? "selected" : '' ?>>xs</option>
                <option <?= ($size == "s")? "selected" : '' ?>>s</option>
                <option <?= ($size == "m")? "selected" : '' ?>>m</option>
                <option <?= ($size == "l")? "selected" : '' ?>>l</option>
                <option <?= ($size == "xl")? "selected" : '' ?>>xl</option>
                <option <?= ($size == "xxl")? "selected" : '' ?>>xxl</option>
            </select>
        </div>
        <div class="form-group"> 
            <select name="gender" class="form-control" required>
                <option disabled selected>Gender of the product...</option>
                <option <?= ($gender == "m")? "selected" : '' ?>>m</option>
                <option <?= ($gender == "f")? "selected" : '' ?>>f</option>
                <option <?= ($gender == "u")? "selected" : '' ?>>u</option>
            </select>
        </div>
        <div class="form-group">
            <label for="product_picture">Product picture</label>
            <input type="file" class="form-control-file" id="product_picture" name="product_picture" > 
            <?php 
                if(isset($update_product)){
                    echo "<input name='actual_picture' value='$picture' type='hidden'>";
                    echo "<img style='width:100px;' src='".URL."uploads/product/$picture'>";
                }
            ?>
        </div>
        <div class="form-group">
            <label for="product_picture2">Second product picture it's optional</label>
            <input type="file" class="form-control-file" id="product_picture2" name="product_picture2">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="price" placeholder="Price of the product..." required value="<?= $price?>">
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="stock" placeholder="Stock of the product..." required value="<?= $stock?>">
        </div>

        <input type="submit" class="btn btn-block btn-info" value="<?= $action ?> product">


    </form>
    
    
        



<?php

    require_once("inc/footer.php");

?>