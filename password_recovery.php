<?php
    require_once("inc/header.php");

    

    if(isset($_POST) && !empty($_POST)){
        if(isset($_GET) && !empty($_GET)){
            if($_POST['password']==$_POST['confirmPassword']){

                $req = "SELECT * FROM user WHERE id_user = :id_user";
                $result = $pdo->prepare($req);
                $result->bindValue(":id_user" , $_GET['id'],PDO::PARAM_STR);
                if($result->execute()){
                    $user = $result->fetch();
                    if($user["pseudo"]==$_POST["pseudo"] && $user["email"]==$_POST["email"]){
                        $confirmString = $user['pseudo'].$user['pwd'].$user['email'];
                        if(password_verify($confirmString,$_GET['target'])){
                            $hashed_pwd = password_hash($_POST['password'],PASSWORD_BCRYPT);
                            $req = "UPDATE user SET pwd = :pwd WHERE id_user = :id_user";
                            $result = $pdo->prepare($req);
                            $result->bindValue(":pwd",$hashed_pwd,PDO::PARAM_STR);
                            $result->bindValue(":id_user",$user['id_user'],PDO::PARAM_STR);
                            if($result->execute()){
                                $msg_error .= "<div class='alert alert-success' role='alert'>Your password was reset successfully <br><a href='login.php'>Click here to go to the login page</a></div>";
                            }else{
                                $msg_error .= "<div class='alert alert-alert' role='alert'>An unexpected error happend</div>";
                            }
                        }else{
                            $msg_error .= "<div class='alert alert-alert' role='alert'>Your recovery link is faulty</div>";
                        }
                    }else{
                        $msg_error .= "<div class='alert alert-alert' role='alert'>Your credentials are wrong</div>";
                    }
                }else{
                    $msg_error .= "<div class='alert alert-alert' role='alert'>Something went wrond</div>";
                }
            }else{
                $msg_error .= "<div class='alert alert-alert' role='alert'>Your Passwords don't match</div>";
            }
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
            <input type="email" class="form-control"  name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control"  name="password" placeholder="Enter your new password" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control"  name="confirmPassword" placeholder="Confirm your new password" required>
        </div>

        <input type="submit" value="Recover" class="btn-block btn btn-success">
    </form>

<?php
    require_once("inc/footer.php");
?>