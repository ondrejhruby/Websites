<?php
    $email = 'Guusdamen@hotmail.com';
    //Auto loader
    require 'includes/PHPMailerAutoload.php';                
    $mail = new PHPMailer(true);
    $mail->isSMTP();                
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth=true;
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; 
    $mail->Username='info.tickify@gmail.com';
    $mail->Password= 'tickifyg10';
    $mail->setFrom('info.tickify@gmail.com','Tickify Ltd');
    $mail->addAddress($email);//use the retrieved user mail to populate the function
    $mail->addReplyTo('info.tickify@gmail.com');
    $mail->isHTML(true);
    $mail->AddEmbeddedImage('img/Attachment-1.png', 'logo');
    $mail->Subject="Your account at Tickify"; 
    $mail->Body="Dear ".$user["fname"]." ".$user["lname"].","."<br>"."You have succesfully created a valid tickify account your username is ".$username.".<br><br>"."Yours sincerely,<br><br>"."Team Tickify";
    $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";

    //$mail->AddEmbeddedImage('img/Attachment-1.png', 'logo');
    //$mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
    

    if(!$mail->send()){
    echo "mail was not sent";
    }else {
    echo "<script>console.log('The mail was sent!')</script>";
    }
    
    ?>