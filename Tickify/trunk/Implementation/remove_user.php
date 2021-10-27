<?php
ob_start(); 
include_once 'connect.php';

if ($_GET) {
    if (isset($_GET['username'])) {
        if ($_POST) {
            if(isset($_POST['yes'])) {                
                $stmt = $conn->prepare("select * from users where username=:username;");
                $stmt->bindValue(':username', $_GET['username'], PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch();  
                
                $sql2 = "DELETE FROM USERS WHERE username=:username;";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bindValue(':username', $_GET['username'], PDO::PARAM_STR);
                $stmt2->execute(); 
                
                require 'includes/PHPMailerAutoload.php';
                //Configure the php mailer 
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                //Configure the mailer object
                $mail->Host='smtp.gmail.com';
                $mail->SMTPAuth=true;
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465; 
                $mail->Username='info.tickify@gmail.com';
                $mail->Password= 'tickifyg10';
                $mail->setFrom('info.tickify@gmail.com','Tickify Ltd');
                $mail->addAddress($user["email"], $user["fname"]." ".$user["lname"]);//use the retrieved user mail to populate the function
                $mail->AddEmbeddedImage('img/Attachment-1.png', 'logo');
                $mail->addReplyTo('info.tickify@gmail.com');
                $mail->isHTML(true);
                $mail->AddEmbeddedImage('img\Attachment-1.png', 'logoimg', 'Attachment-1.png');
                $mail->Subject="Account suspension"; 
                $mail->Body="Dear ".$user["fname"]." ".$user["lname"].","."<br><br>"."You have been removed from the tickify service, because of violation of our code of conduct .<br><br>"."Yours sincerely,<br><br>"."Your Tickify Team";  
                $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
                
                if(!$mail->send()){

                    echo "mail was not sent";
                }else {
                    header('Location: /removal.html');
                    exit();
                    echo "<script>console.log('The mail was sent!')</script>";
                }
                
                
            }
        }
    }
        
    


    
        /*

         * ¨check if username and yes field are present|
         * perform delete query for given username|
         * redirect to success page|
         * ¨ */
        
    }


    ?>
<!DOCTYPE html>
<html>
    <head>
        <title> Confirmation page </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/form.css">
    </head>
    <body>
    <form action="" method="post">
        <fieldset>
            <legend>Remove the user?</legend>        
         
            <input type="submit" name="yes" value="YES">
            <a href="warning.html">
                <input type="submit" value="NO"> 
            </a> <br>
            <a href="administrator.php">
                <input type="submit" value="Back"> 
            </a>
        </fieldset>
    </form>
    </body>
</html>
