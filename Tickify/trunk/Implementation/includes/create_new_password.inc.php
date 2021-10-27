<?php 
    ob_start();
    require_once "../connect.php";

if (isset($_POST["password_reset_button"])) { 

    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password_repeat"];   

    if (empty($password) || empty($passwordRepeat)) {
        header('Location: ../create_new_password.php?"newpsword=empty');
        exit();
    } elseif ($password != $passwordRepeat) {
        header('Location: ../create_new_password.php?"newpsword=pswordnotsame');
        exit();
    }

    $currentDate = date("U");

    $sql = "SELECT * FROM pswordreset WHERE pswordselector=:selector AND pswordexpdate>=:currentDatee";
    $stmt = $conn->prepare($sql);
    if (!$conn->prepare($sql)) {
        echo "Error occured";
        exit();
    } else {
        $stmt->bindParam(':selector', $selector, PDO::PARAM_STR);
        $stmt->bindParam(':currentDatee', $currentDate, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        
        if (empty($result)) {
            echo "Token has expired, request your recovery again(10min)";
            exit();
        } else {
            $tokenDump = hex2bin($validator);
            $tokenCheck = password_verify($tokenDump, $result["pswordtoken"]);

            if ($tokenCheck === false) { 
                echo "It's not the same";
                exit();
                
            } elseif ($tokenCheck === true) {

                $tokenEmail = strtolower($result['pswordemail']);

                $sql = "SELECT * FROM users WHERE email=:tokenEmail";
                $stmt = $conn->prepare($sql);
                if (!$conn->prepare($sql)) {
                    echo "There was an error!";
                    exit();
                } else {
                    $stmt->bindParam(':tokenEmail', strtolower($tokenEmail), PDO::PARAM_STR);
                    $stmt->execute();   
                    $result = $stmt->fetch();                          
                    if (empty($result)) {
                    echo "There was an error!";
                    exit();
                } else {   

                    $sql = "UPDATE users SET password=:newPassword WHERE email=:tokenEmail";
                    $stmt = $conn->prepare($sql);
                if (!$conn->prepare($sql)) {
                    echo "There was an error!";
                    exit();
                } else {
                    $newlyMadeHashPwd = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->bindParam(':newPassword', $newlyMadeHashPwd, PDO::PARAM_STR);
                    $stmt->bindParam(':tokenEmail', strtolower($tokenEmail), PDO::PARAM_STR);
                    $stmt->execute();    
                    
                    $sql = "DELETE FROM pswordreset WHERE pswordemail=:tokenEmail";
                    $stmt = $conn->prepare($sql);
                    if (!$conn->prepare($sql)) {
                        echo "There was an error!";
                        exit();
                    } else {
                        $stmt->bindParam(':tokenEmail', strtolower($tokenEmail), PDO::PARAM_STR);
                        $stmt->execute();         
                        header("Location: ../login.php?newpwd=passwordupdated");
                    }
                        $contacturl = 'localhost:80/contact.php';
                        require 'PHPMailerAutoload.php';
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host='smtp.gmail.com';
                        $mail->SMTPAuth=true;
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465; 
                        $mail->Username='info.tickify@gmail.com';
                        $mail->Password= 'tickifyg10';
                        $mail->setFrom('info.tickify@gmail.com','Tickify Ltd');
                        $mail->addAddress($result["email"]);
                        $mail->AddEmbeddedImage('../img/Attachment-1.png', 'logo');
                        $mail->addReplyTo('info.tickify@gmail.com');
                        $mail->isHTML(true);
                        $mail->Subject="Password reset Tickify"; 
                        $mail->Body = '<p> Dear user, </p>';
                        $mail->Body .= '<p> We received a request to change your password. If you did not do this, contact us: </p>';          
                        $mail->Body .= '<a href="' . $contacturl . '">' .$contacturl . '</a></p>';                        
                        $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
                    }
                    if(!$mail->send()){
                        echo "mail was not sent";
                    }else {
                        echo "<script>console.log('The mail was sent!')</script>";
                    }
                    
                }  
            }
        }
    }
}
}




?>