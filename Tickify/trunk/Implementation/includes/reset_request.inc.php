<?php 
    ob_start();
    require_once "../connect.php";

    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$_POST['email']) {
            $errors[] = 'Missing email!';
            header("Location:/reset_password.php?signup=empty");
            exit();
        }
            $sql = "SELECT email FROM users WHERE email=:email";    
            $stmt = $conn->prepare($sql); 
            $stmt->bindParam(':email', strtolower($_POST['email']), PDO::PARAM_STR);    

        try {
            $stmt->execute();
            $email = $stmt->fetch();
            if (empty($email)) {
                $errors[] = 'Not in DBS!';
                header("location:/reset_password.php?signup=empty");
                exit();   
            }         
          
    } catch (PDOException $ex) 
    {

    } if (count($errors) === 0) {
}
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "localhost:80/create_new_password.php?selector=" . $selector . 
    "&validator=" . bin2hex($token);

    $tokenExpDate = date("U") + 600;    

    $sql = "DELETE FROM pswordreset WHERE pswordemail=:email";
    $stmt = $conn->prepare($sql);

    if (!$conn->prepare($sql)) {
        echo "There was an error!";
        exit();

    } else {

    $stmt->bindParam(':email', strtolower($_POST['email']), PDO::PARAM_STR);  
    $stmt->execute();

    }
    $sql = "INSERT INTO pswordreset (pswordemail, pswordselector, pswordtoken, pswordexpdate) VALUES
            (:email, :selector, :hashedToken, :expireDate);";

    $stmt = $conn->prepare($sql);

    if (!$conn->prepare($sql)) {
        echo "Insert error";
        exit();
    } else {
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        $stmt->bindParam(':email', strtolower($_POST["email"]), PDO::PARAM_STR);
        $stmt->bindParam(':selector', $selector, PDO::PARAM_STR);
        $stmt->bindParam(':hashedToken', $hashedToken, PDO::PARAM_STR);
        $stmt->bindParam(':expireDate', $tokenExpDate, PDO::PARAM_STR);
        $stmt->execute();
    }
    
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
$mail->addAddress(strtolower($_POST["email"]));
$mail->AddEmbeddedImage('../img/Attachment-1.png', 'logo');
$mail->addReplyTo('info.tickify@gmail.com');
$mail->isHTML(true);
$mail->Subject="Password reset Tickify"; 
$mail->Body = '<p> Dear user, </p>';
$mail->Body .= '<p> We have received a request to change your password.
if you did not create this request, you are free to ignore this message</p>';
$mail->Body .= '<p> Your reset link as asked for: </br>';
$mail->Body .= '<a href="' . $url . '">' .$url . '</a></p>';
$mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";

//redirect to succes page

if(!$mail->send()){

    echo "mail was not sent";

}

    echo "<script>console.log('The mail was sent!')</script>";
}


    
