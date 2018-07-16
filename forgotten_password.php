<?php
    require_once("inc/header.php");

    if(isset($_POST) && !empty($_POST)){
        $req = "SELECT * FROM user WHERE pseudo = :pseudo and email = :email";
        $result = $pdo->prepare($req);
        $result->bindValue(":pseudo",$_POST['pseudo'],PDO::PARAM_STR);
        $result->bindValue(":email",$_POST['email'],PDO::PARAM_STR);
        if($result->execute()){
            $user= $result->fetch();
            $confirmString = $user['pseudo'].$user['pwd'].$user['email'];
            $confirmHash = password_hash($confirmString,PASSWORD_BCRYPT);
            $receiver = $user['email'];
            $titel = "Mail from MyEshop.com |Password recovery";
            $recoverMailHeader  = "FROM: no-reply@myeshop.com \r\n";
            $recoverMailHeader  .= "Reply-To: $receiver \r\n";
            $recoverMailHeader  .= "MIME-Version: 1.0\r\n"; 
            $recoverMailHeader  .= "Content-type: text/html; charset=iso-8859-1 \r\n";
            $recoverMailHeader  .= "X-Mailer: PHP/" . phpversion();

            $recoverMailMessage = "<h1> Recover Mail | FROM MyEshop.com</h1>";
            $recoverMailMessage .= "<h2> Hi $user[pseudo] <h2>";
            $recoverMailMessage .= "<p> Click on the following link to change your password</p";
            $recoverMailMessage .= "<a href='password_recovery.php?id=$user[id_user]&target=$confirmHash'>";
            $recoverMailMessage .= "Go to this page to get a new password</a>";

            if(mail($receiver, $titel, $recoverMailMessage, $recoverMailHeader)){
                $msg_error .= "<div class='alert alert-success' role='alert'>You got a mail to get a new password</div>";
            }else{
                $msg_error .= "<div class='alert alert-alert' role='alert'>Something went wrong try again</div>";
            }

        }

    }

    $page= "Forgotten Password";
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
        <input type="submit" value="Recover" class="btn-block btn btn-success">
    </form>

<?php
    require_once("inc/footer.php");
?>