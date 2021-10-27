<?php 
    ob_start();
    require_once 'connect.php';

    $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$_POST['name']) {
        $errors[] = 'Missing your name!';
    }

    if (!$_POST['email']) {
        $errors[] = 'Missing your email!';
    }

    if (!$_POST['comment']) {
        $errors[] = 'Missing your comment!';
    }

    if (count($errors) === 0) {         

    //Auto loader
    $email = $_POST['email'];
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    require_once 'includes/PHPMailerAutoload.php';
    // onfigure the php mailer 
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    // Configure the mailer object
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth=true;
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; 
    $mail->Username='info.tickify@gmail.com'; // email
    $mail->Password= 'tickifyg10'; // password
    $mail->setFrom('info.tickify@gmail.com','Tickify Ltd');    
    $mail->addAddress($email, $name); //use the retrieved user mail to populate the function
    $mail->addReplyTo('info.tickify@gmail.com');
    $mail->isHTML(true);
    $mail->Subject="Your account at Tickify"; 
    $mail->Body="Dear ".$name.","."<br><br>"."Your submission has succesfully been received! Our team will come back to you as soon as possible <br><br> Yours sincerely, <br><br> Your Tickify Team";
    $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
    if(!$mail->send()){
        echo "mail was not sent";
    }else {
        sleep(5);
        $mail->ClearAllRecipients(); 
        $message2 = "New support question from: " .$name.", email: ".$email.",<br><br> Enquiry: ".$comment."";
        $mail->Body = $message2;
        $mail->AddAddress('info.tickify@gmail.com');
        $mail->send();
        header("location:index.php");
        ob_end_flush();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <title>Contact us</title>
</head>

<body>
    <form action="" method="post">
        <fieldset>
            <legend> Contact us </legend>

            <label class="title" for="name"> Name </label>
            <input type="text" name="name" id="name"> <br>


            <label class="title" for="email"> Email </label>
            <input type="email" name="email" id="email"> <br>

            <label class="title" for="comment"> Comment </label>
            <textarea name="comment" id="comment" rows="5" cols="34"></textarea>
            <input type="submit" name="submit" value="submit">
        </fieldset>
    </form>

    <?php  
      foreach($errors as $error): ?>
    <p>
        <?php print $error;?>
    </p>

    <?php endforeach; ?>

</body>

</html>