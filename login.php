<?php
    require_once("inc/header.php");

    $page= "Login";

    if($_POST){
        if(filter_var($_POST['pseudo'],FILTER_VALIDATE_EMAIL)){
            $req="SELECT * FROM user WHERE email= :pseudo";
        }else{

            $req="SELECT * FROM user WHERE pseudo= :pseudo";
        }

        $result= $pdo->prepare($req);
        $result->bindValue(":pseudo",$_POST['pseudo'],PDO::PARAM_STR);

        $result->execute();

        if($result ->rowCount() >0){ // if we select a pseudo in the DTB
            $user = $result ->fetch();

            if (password_verify($_POST['password'],$user['pwd'])) { // function password_verify() is link to password_hash(). It allows us to check the correspondance between 2 values: 1st argument will be the value to check, 2nd argument will be the match value
                // $_SESSION['pseudo'] = $user['pseudo']
                foreach ($user as $key => $value) {
                    if($key != 'pwd'){
                        $_SESSION['user'][$key] = $value;

                        header('location:profile.php');
                    }
                }
            }else{
                $msg_error .= "<div class='alert alert-danger' role='alert'>Identification error, please try again.</div>";
            }
        }else{
            $msg_error .= "<div class='alert alert-danger' role='alert'>Identification error, please try again.</div>";
        }

    }
?>


    <h1><?= $page ?></h1>
    <?= $msg_error ?>
    <form action="" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="pseudo" placeholder="Enter your pseudo" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control"  name="password" placeholder="Enter your password" required>
        </div>
        <a href="forgotten_password.php"><small>Forgot your password?</small></a>
        <input type="submit" value="Login" class="btn-block btn btn-success">
    </form>

<?php
    require_once("inc/footer.php");
?>