<?php 
    ob_start();
    session_start();
    require_once "connect.php";
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/form.css"> 
    <title>Reset your password</title>
</head>
<body>
    <form action="includes/reset_request.inc.php" method="post">
        <fieldset>
            <legend>Reset your password</legend>        
            <p><label class="title" for="email"> email </label>
                <input type="email" name="email" id="email">   <br />
                <div class="submit"><input type="submit" name="password_reset_button" value="Send password"></div>
                <?php 
                $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                if (!isset($_GET['signup'])) {
                    exit();
                }
                else {
                    $signupCheck = $_GET['signup'];

                    if ($signupCheck == "empty") {
                        echo "<p class='error'>You are not registered!</p>";
                        exit();
                    }
                    elseif ($signupCheck == "email") {
                        echo "<p class='error'>You did not fill in your email!</p>";
                        exit();
                    }
                }
                ?>
        </fieldset>
    </form>  
    

        
</body>
</html>