<?php
ob_start();
require_once 'connect.php';
if (!isset($_SESSION["attempts"]))
        $_SESSION["attempts"] = 0;

session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$_POST['username']) {
        $errors[] = 'Missing username!';
        header('Location: ../login.php?signin=missingusername');        
    }
    if (!$_POST['password']) {
        $errors[] = 'Missing password!';
        header('Location: ../login.php?signin=missingpword&username='.$_POST['username']);
    }
    if (count($errors) === 0) {        
        
        $sql = "SELECT * FROM users WHERE username ILIKE :username;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
// $stmt->bindParam(':passhash', hash('sha512', $_POST['password']), PDO::PARAM_STR);
        try {
            $stmt->execute();
            $user = $stmt->fetch();
            if (!$user) {
                $errors[] = 'Invalid credentials!';
                header('Location: ../login.php?signin=invalidusername');
            } else {                
                if (!password_verify($_POST['password'], $user['password'])) {
                    $errors[] = 'Invalid credentials!';
                    $_SESSION["attempts"] = $_SESSION["attempts"] + 1;
                    header('Location: ../login.php?signin='.'wrongpword&username='.$_POST['username']);
                    exit();
                } else /* successful login */ {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION["attempts"] = 0;
                    header("Location:index.php");
                    ob_end_flush();                
                }
            }
        } 
        catch (PDOException $ex) {
        }
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
    <title>Sign in</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/form.css">
</head>

<body>
<form action="" method="post">
    <fieldset>
        <legend>Log in</legend>
        
        <p><label class="title" for="username"> Username </label>
            
            <?php 
                if (isset($_GET['username'])) {
                $uname = $_GET['username'];
                echo '<input type="text" name="username" id="username" value="'.$uname.'"><br />';
            } else {
                echo '<input type="text" name="username" id="username"><br />';
            }
            ?>
        
            <label class="title" for="password"> Password </label>
            <input type="password" name="password" id="password">   <br />
            <div class="submit"><input type="submit" name="submit" value="Log in"></div>  

        </fieldset>
        <?php $counter = $_SESSION["attempts"];
            if ($counter > 4) {
            echo "<fieldset id=errorbox><div class=alert><p class='error'><a href=reset_password.php style=text-decoration:none>Forgot password?</a></p>";
                exit();
        }
            ?>
        
        <?php 
                $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                if (!isset($_GET['signin'])) {
                    exit();
                }
                else {
                    
                    $signupCheck = $_GET['signin'];

                    if ($signupCheck == "invalidusername") {
                        
                        echo "<fieldset id=errorbox><div class=alert><p class='error'><a href=registration.php class=error style=text-decoration:none>You are not registered, sign up here!</a></p></div></fieldset>";
                        exit();                    
                    
                    }
                }
                ?>
    </form>          
</body>
</html>
