<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/footercss.css">
</head>
<body>
    <footer>
        <div id="footer">
                <div class="lookWrap">
                  <div id="look">
                    <div class="section">
                      <h3>Support</h3>
                      <a href="#">FAQs</a>
                      <a href="contact.php">Contact Us</a>
                      <a href="#">Privacy Policy</a>
                    </div>
                    <div class="section">
                      <h3>Follow Us</h3>
                      <a href="http://www.facebook.com">Facebook</a>
                      <a href="http://www.twitter.com">Twitter</a>        
                      <a href="http://www.pinterest.com">Pinterest</a>
                      <a href="http://www.linkedin.com">LinkedIn</a>
                      <a href="http://www.plus.google.com">Google+</a>
                    </div>
                    <div class="section">
                      <h3>Newsletter sign up</h3>
                      <form action="" method="post">
                      <input type="email" name="email" id="email" placeholder="Enter email address"><input type="submit" name="newslettersubmit" value="Sign up!">
                      </form>
                    </div>      
                  </div>
                </div>
                <div class="legality">
                      © Copyright 2018 - 2018 Tickify
                    </div>
              </div>
              <?php
              if (isset($_POST['newslettersubmit'])) {
                
    $email = $_POST['email'];
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
    $mail->Body="Dear, <br><br>"."You have succesfully signed up for the Tickify newsletter! You will be kept up to date on your favorite events! <br><br>Yours sincerely,<br><br>"."Team Tickify";
    $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";

    //$mail->AddEmbeddedImage('img/Attachment-1.png', 'logo');
    //$mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
    

    if(!$mail->send()){
    echo "mail was not sent";
    }else {
    echo "<script>console.log('The mail was sent!')</script>";
    }
  }
    
    ?>
        </footer>
</body>
</html>