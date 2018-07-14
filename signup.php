<?php
    require_once("inc/header.php");

    $page = "Signup";
    
    $privilege_view= '';

    $direction ='';

    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id']==$_SESSION['user']['id_user'] || userAdmin())){
        $req = "SELECT * FROM user WHERE id_user = :id_user";
        $result = $pdo->prepare($req);
        $result->bindValue(':id_user', $_GET['id'] , PDO::PARAM_INT );
        $result->execute();
        if($result->rowCount() == 1){
            $user = $result->fetch();
            $pseudo = $user['pseudo']; 
            $firstname = $user['firstname'];
            $lastname = $user['lastname'];
            $email = $user['email'];
            $address = $user['address'];
            $zip_code = $user['zip_code'];
            $city = $user['city'];
            $gender = $user['gender'];
            $password = $user['pwd'];
            $action = "Update";
            $privilege = $user['privilege'];

        }
        $direction .= '?id=';
        $direction .= $_GET['id'];

    }elseif(userConnect()){
        $req = "SELECT * FROM user WHERE id_user = :id_user";
        $result = $pdo->prepare($req);
        $result->bindValue(':id_user', $_SESSION['user']['id_user'] , PDO::PARAM_INT );
        $result->execute();
        $user = $result->fetch();
        $pseudo = $user['pseudo']; 
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $email = $user['email'];
        $address = $user['address'];
        $zip_code = $user['zip_code'];
        $city = $user['city'];
        $gender = $user['gender'];
        $password = $user['pwd']; 
        $action = "Update"; 
        $privilege = 0;      

        $direction .= '?id=';
        $direction .= $_SESSION['user']['id_user'];
        
    }else {
        
        //Reload the values entered by the user if problem during page reloading
        $pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : ''; // if we receive a POST, the variable will keep the value or if no POST, value = empty
        $firstname = (isset($_POST['firstname'])) ? $_POST['firstname'] : '';
        $lastname = (isset($_POST['lastname'])) ? $_POST['lastname'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $address = (isset($_POST['address'])) ? $_POST['address'] : '';
        $zip_code = (isset($_POST['zipcode'])) ? $_POST['zipcode'] : '';
        $city = (isset($_POST['city'])) ? $_POST['city'] : '';
        $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
        $password = '';
        $action = "Register";
        $privilege= 0;
        
        
    }
    
    if(userAdmin()){

        $privilege_view .= '<div class="form-group"> ';

        $privilege_view .= '<select name="privilege" class="form-control">';
        if(isset($privilege)&& $privilege==1){
            $privilege_view .=  '<option value="0" selected>Not Admin</option>';
            $privilege_view .=  '<option value="1" >Admin</option>';
        }else{
            $privilege_view .=  '<option value="0" >Not Admin</option>';
            $privilege_view .=  '<option value="1" selected>Admin</option>';
        }
        
        $privilege_view .= "</select>";
        $privilege_view .= "</div>";
    }


    if($_POST){
        // check pseudo
        if(!empty($_POST["pseudo"])){
            $pseudo_verif = preg_match('#^[a-zA-Z0-9-._]{3,20}$#',$_POST["pseudo"]); //function preg_match() allows me to check what info will be allowed in a result. It takes 2 arguments: REGEX + the string to check. At the end, I will have a TRUE or FALSE condition

            if(!$pseudo_verif){
                $msg_error .= "<div class='alert alert-danger' role='alert'>Your pseudo should contain letters (upper/lower) and numbers. It should be between 3 and 20 characters long and only '.' and '_' are accepted. Please try again!</div>";
            }
        }else{
            $msg_error .= "<div class='alert alert-danger' role='alert'>Please enter a valid pseudo</div>";
        }

        // check password
        if(!empty($_POST["password"]) ){
            $password_verif = preg_match('#^(?=.*[A-Z])(?=.*[A-Z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#',$_POST["password"]); //it means we ask between 6 to 15 characters + 1 UPPER + 1 LOWER + 1 number + 1 symbol

            if(!$password_verif){
                $msg_error .= "<div class='alert alert-danger' role='alert'>Your password should contain one uppercase, one lowercase, one number and a special symbol </div>";
            }
        }elseif($action == "Update"){

        }else{
            $msg_error .= "<div class='alert alert-danger' role='alert'>Please enter a valid password</div>";
        }

        // check email
        if(!empty($_POST["email"])){
            $email_verif = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL); //function filter_var() allows us to check a result (STR -> email, URL). It takes 2 arguments the result to check + the method. It returns a BOOLEAN

            $forbidden_mails = [
                'mailinator.com',
                'yopmail.com',
                'mail.com'
            ];

            $email_domain = explode('@', $_POST['email'])[1]; // function explode() allows me to explode into 2 parts regarding the element I've chosen


            if(!$email_verif || in_array($email_domain,$forbidden_mails)){
                $msg_error .= "<div class='alert alert-danger' role='alert'>Please enter a valid email</div>";
            }

        }else{
            $msg_error .= "<div class='alert alert-danger' role='alert'>Please enter a valid email</div>";
        }

        if(!isset($_POST['gender']) || ($_POST['gender'] != "m" && $_POST['gender'] != "f" && $_POST['gender'] != "o")){
            $msg_error .= "<div class='alert alert-danger' role='alert'>Choose a valid gender.</div>";
        }


        // OTHER CHECKS POSSIBLE HERE

        if(empty($msg_error)){
            // check if pseudo is free
            $result = $pdo->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
            $result->bindValue(':pseudo' , $_POST['pseudo'],PDO::PARAM_STR);
            $result->execute();
            if($result->rowCount()==1 && !userConnect()){
                $msg_error .= "<div class='alert alert-secondary' role='alert'>The pseudo $_POST[pseudo] is already taken, please choose another one.</div>";
            }else{
                if(userConnect()){
                    $result = $pdo->prepare("UPDATE user SET pseudo = :pseudo, firstname = :firstname, lastname = :lastname, email =:email, gender = :gender, city = :city, zip_code =:zip_code, address =:address, privilege = :privilege WHERE id_user = :id_user ");
                    $result->bindValue(':privilege' , $privilege,PDO::PARAM_INT);
                    $result->bindValue(':id_user',$_GET['id'],PDO::PARAM_INT);

                }else {
                    $result = $pdo->prepare("INSERT INTO user (pseudo,pwd , firstname, lastname, email, gender, city, zip_code, address, privilege) VALUES (:pseudo, :pwd, :firstname, :lastname, :email, :gender, :city, :zip_code, :address, 0)");
                    
                }

                if(isset($_POST["password"])){
                    $hashed_pwd = password_hash($_POST['password'],PASSWORD_BCRYPT); // function password_hash() allows us to encrypt the password in a way securer way than md5. It takes 2 arguments: the result to hash, the method
                    $result->bindValue(':pwd' , $hashed_pwd,PDO::PARAM_STR);
                }else{

                }
                $result->bindValue(':pseudo' , $_POST['pseudo'],PDO::PARAM_STR);
                $result->bindValue(':firstname' , $_POST['firstname'],PDO::PARAM_STR);
                $result->bindValue(':lastname' , $_POST['lastname'],PDO::PARAM_STR);
                $result->bindValue(':email' , $_POST['email'],PDO::PARAM_STR);
                $result->bindValue(':gender' , $_POST['gender'],PDO::PARAM_STR);
                $result->bindValue(':city' , $_POST['city'],PDO::PARAM_STR);
                $result->bindValue(':address' , $_POST['address'],PDO::PARAM_STR);
                $result->bindValue(':zip_code' , $_POST['zipcode'],PDO::PARAM_STR);


                if($result->execute()){
                    if(userConnect() && $_SESSION['user']['id_user']== $_GET['id']){
                        foreach ($_POST as $key => $value) {
                            if($key != 'password'){
                                $_SESSION['user'][$key] = $value;
                                header('location:profile.php');
                            }
                        }
                    }elseif(userAdmin()){
                        header("location:admin/user_list.php?update=1");
                    }else{
                        header("location:login.php");
                    }
                }
            }

        }

    }



    
    //debug($_SESSION)
?>

    <h1><?= $page ?></h1>
    <form action="<?= $direction ?>" method="post">
        <?= userConnect() ?'':'<small class="form-text text-muted">We\'ll never use your data for commercial use</small>'?>
        <?= $msg_error ?>
        <div class="form-group">
            <input type="text" class="form-control" name="pseudo" value="<?= $pseudo?>" placeholder="Choose a pseudo..." required>
        </div>
        <?php if(!isset($_GET['id']) && !userConnect()) :?>
        <div class="form-group">
            <input type="password" class="form-control"  name="password" value="<?= $password?>" placeholder="Choose a password..." required>
        </div>
        <?php endif ?>
        <div class="form-group">
            <input type="text" class="form-control" name="firstname" value="<?= $firstname?>" placeholder="Your firstname" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="lastname" value="<?= $lastname?>" placeholder="Your lastname" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" value="<?= $email?>" placeholder="Your email" required>
        </div>
        <div class="form-group"> 
            <select name="gender" class="form-control">
                <option value="m" <?= ($gender=="m")? "selected" : '' ?> >Male</option>
                <option value="f" <?= ($gender=="f")? "selected" : '' ?> >Female</option>
                <option value="o" <?= ($gender=="o")? "selected" : '' ?> >Other</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="address" value="<?= $address?>" placeholder="Address..." required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="zipcode" value="<?= $zip_code?>" placeholder="Zip code..." required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="city" value="<?= $city?>" placeholder="Your city" required>
        </div>
        <?= $privilege_view ?>
        <input type="submit" class="btn btn-block btn-success" value="<?= $action?>">
    </form>

<?php
    require_once("inc/footer.php");
?>