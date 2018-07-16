<?php
    require_once("inc/header.php");
    if(userConnect()){
        if(isset($_POST) && !empty($_POST)){
            if($_POST['confirmPassword']== $_POST['password']){
                $password_verif = preg_match('#^(?=.*[A-Z])(?=.*[A-Z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#',$_POST["password"]); 
                if($password_verif){
                    $req = "SELECT pwd FROM user WHERE id_user = :id_user";
                    $result = $pdo->prepare($req);
                    $result->bindValue(":id_user",$_SESSION['user']['id_user'],PDO::PARAM_STR);
                    if($result->execute()){
                        $oldPassword = $result->fetch();
                        if(password_verify($_POST['oldPassword'],$oldPassword['pwd'])){
                            $hashed_pwd = password_hash($_POST['password'],PASSWORD_BCRYPT);
                            $req = "UPDATE user SET pwd = :pwd WHERE id_user = :id_user";
                            $result = $pdo->prepare($req);
                            $result->bindValue(":pwd",$hashed_pwd,PDO::PARAM_STR);
                            $result->bindValue(":id_user",$_SESSION['user']['id_user'],PDO::PARAM_STR);
                            if($result->execute()){
                                $msg_error .= "<div class='alert alert-success' role='alert'>Your password was updated successfully <br><a href='profile.php'>Click here to go back to your profile</a></div>";
                            }
                        }else{
                            $msg_error .= "<div class='alert alert-danger' role='alert'>Your old password was wrong</div>";
                        }
                    }else{
                        $msg_error .= "<div class='alert alert-warning' role='alert'>Some error happend try again</div>";
                    }
                }else{
                    $msg_error .= "<div class='alert alert-danger' role='alert'>Your new password doesn't match the reuirements</div>";
                }
            }else{
                $msg_error .= "<div class='alert alert-danger' role='alert'>Both your new passwords don't match</div>";
            }
        }
        
    }else{
        header('location:signup.php');

    }

    $page= "Update your password";
?>


    <h1><?= $page?></h1>
    <?= $msg_error ?>
    <form action="" method="post">
        <div class="form-group text-left">
            <label for="oldPassword" >Enter your old password</label>
            <input type="password" class="form-control" name="oldPassword" placeholder="Enter your old password"   id="oldPassword" required>
        </div>
        <div class="form-group text-left">
            <label for="password">Enter your new password</label>
            <input type="password" class="form-control"  name="password" placeholder="Enter your new password" id="password" required>
        </div>
        <div class="form-group text-left">
            <label for="confirmPassword">Confirm your new password</label>
            <input type="password" class="form-control"  name="confirmPassword" placeholder="Confirm your new password" id="confirmPassword" required>
        </div>
        <input type="submit" value="Update" class="btn-block btn btn-success">
    </form>
    

<?php
    require_once("inc/footer.php");
?>