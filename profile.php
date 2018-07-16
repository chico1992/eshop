<?php
    require_once("inc/header.php");
    require_once("admin/inc/functions.php");


    $page= "My Profile";


    if(!userConnect()){
        header('location:login.php');
        exit();
    }

//debug($_SESSION['user']);
//debug($_FILES);

if(!empty($_FILES['user_picture']['name'])){
        $picture_name =  $_SESSION['user']['firstname'] . $_SESSION['user']['lastname'] .'_' . $_FILES['user_picture']['name'];
        $picture_name = str_replace(' ','-',$picture_name);
        $picture_path = ROOT_TREE . 'uploads/user/'.$picture_name;

        $result = $pdo->prepare("UPDATE user SET picture=:picture WHERE id_user = :id_user");
        $result->bindValue(':id_user',$_SESSION['user']['id_user'],PDO::PARAM_INT);
        $result->bindValue(':picture',$picture_name,PDO::PARAM_STR);
        if($result->execute()){
            if(!empty($_FILES['user_picture']['name'])){
                copy($_FILES['user_picture']['tmp_name'],$picture_path);
            }
        }
        $_SESSION['user']['picture'] = $picture_name;
        header('location:profile.php');

}


if($_GET){
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['context']) && $_GET['context'] == 'user'){
            
        delete($_GET['id'] , $_GET['context']);

        unset($_SESSION['user']);
        header('location:index.php');

        
        
    }else{
        $message= "<div class='alert alert-danger' role='alert'>The delete failed</div>";
    }
    
}




?>


    <h1><?= $page ?></h1>
    <p>Please find your information below:</p>
    <ul class="list-group list-group-flush" style="list-style-type: none;">
        <li class="list-group-item">Firstname: <?= $_SESSION['user']['firstname']?></li>
        <li class="list-group-item">Lastname: <?= $_SESSION['user']['lastname']?></li>
        <li class="list-group-item">Email: <?= $_SESSION['user']['email']?></li>
        <li><img style='width:120px; border-radius:50%;' src='<?= URL."uploads/user/".$_SESSION['user']['picture']?>'></li>
        
    </ul>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="input-group input-group-lg">
            
            <label for="user_picture">Profile picture</label>
            <input type="file" class="input-group mb-3" id="user_picture" name="user_picture" > 
            <input type="submit" class="btn btn-block btn-info" value="Update your photo">
            

        </div>
    </form>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <a href='password_update.php'=><button type="button" class="btn btn-success btn-lg">Update Your password</button></a>
        <a href='signup.php'=><button type="button" class="btn btn-primary btn-lg">Update Your Infos</button></a>
        <a data-toggle='modal' data-target='#deleteModal<?=$_SESSION['user']['id_user']?>'><button type="button" class="btn btn-danger btn-lg">Delete Your profile</button></a>
        <?=deleteModal($_SESSION['user']['id_user'] , $_SESSION['user']['pseudo'], 'user');?>
        
    </div>



    
<?php
    require_once("inc/footer.php");
?>