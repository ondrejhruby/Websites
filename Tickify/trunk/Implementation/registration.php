<?php
    ob_start();
include_once 'connect.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $register_attempt = true;
    if (!$_POST['username']) {
        $errors[] = 'Missing username!';
        header('Location: ../registration.php?signup=missingusername');  
    }
    
    elseif (!$_POST['password']) {
        $errors[] = 'Missing password!';
        header('Location: ../registration.php?signup=missingpassword'); 
    }
    elseif (!$_POST['password_repeat']) {
        $errors[] = 'Missing password_repeat!';
        header('Location: ../registration.php?signup=missingpasswordrepeat'); 
    }
    elseif ($_POST['password'] !== $_POST ['password_repeat']) {
        $errors[] = 'The passwords have to match!';
        header('Location: ../registration.php?signup=passwordsnotsame'); 
    }
    elseif (!$_POST['country']) {
        $errors[] = 'Missing country!';
        header('Location: ../registration.php?signup=missingcountry'); 
    }
    elseif (!$_POST['city']) {
        $errors[] = 'Missing city!';
        header('Location: ../registration.php?signup=missingcity'); 
    }
    elseif (!$_POST['postalcode']) {
        $errors[] = 'Missing postalcode!';
        header('Location: ../registration.php?signup=missingpostalcode'); 
    }
    elseif (!$_POST['street']) {
        $errors[] = 'Missing street!';
        header('Location: ../registration.php?signup=missingstreet'); 
    }
    elseif (!$_POST['houseno']) {
        $errors[] = 'Missing House Number!';
        header('Location: ../registration.php?signup=missinghousenr'); 
    }
    elseif (!$_POST['firstname']) {
        $errors[] = 'Missing First Name!';
        header('Location: ../registration.php?signup=missingfirstname'); 
    }
    elseif (!$_POST['lastname']) {
        $errors[] = 'Missing Last Name!';
        header('Location: ../registration.php?signup=missinglastname'); 
    }
    elseif (!$_POST['sex']) {
        $errors[] = 'Missing sex!';
        header('Location: ../registration.php?signup=missingsex'); 
    }
    elseif (!$_POST['email']) {
        $errors[] = 'Missing E-mail!';
        header('Location: ../registration.php?signup=missingemail'); 
    }
    elseif (!$_POST['bdate']) {
        $errors[] = 'Missing Birthdate!';
        header('Location: ../registration.php?signup=missingbdate'); 
    }
    elseif (!$_POST['utype']) {
        $errors[] = 'Missing Type of user!';
        header('Location: ../registration.php?signup=missingutype'); 
    }
    
    elseif (date("Y-m-d") < $_POST['bdate']) {
        $errors[] = 'bdate is higher than today';
        header('Location: ../registration.php?signup=datenotcorrect'); 

    } else {  
    
    
    if (count($errors) === 0) {
        header('Location: ../registration.php?signup=success'); 
        $sql = "insert into users(username, password, country, city, postal_code, street, house_nr, fname, minit, lname, sex, email, bdate, type_user)values(:username, :password, :country, :city, :postalcode, :street, :houseno, :firstname, :minit, :lastname, :sex, :email, :bdate, :utype);";
        $stmt = $conn->prepare($sql);         
        $stmt->bindParam(":username", htmlspecialchars($_POST["username"]), PDO::PARAM_STR);        
        $stmt->bindParam(":password", password_hash($_POST["password"], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindParam(":country", htmlspecialchars($_POST["country"]), PDO::PARAM_STR);
        $stmt->bindParam(":city", htmlspecialchars($_POST["city"]), PDO::PARAM_STR);
        $stmt->bindParam(":postalcode", htmlspecialchars($_POST["postalcode"]), PDO::PARAM_STR);
        $stmt->bindParam(":street", htmlspecialchars($_POST["street"]), PDO::PARAM_STR);
        $stmt->bindParam(":houseno", htmlspecialchars($_POST["houseno"]), PDO::PARAM_STR);
        $stmt->bindParam(":firstname", htmlspecialchars($_POST["firstname"]), PDO::PARAM_STR);
        $stmt->bindParam(":minit", htmlspecialchars($_POST["minit"]), PDO::PARAM_STR);
        $stmt->bindParam(":lastname", htmlspecialchars($_POST["lastname"]), PDO::PARAM_STR);
        $stmt->bindParam(":sex", htmlspecialchars($_POST["sex"]), PDO::PARAM_STR);
        $stmt->bindParam(":email", htmlspecialchars(strtolower($_POST["email"])), PDO::PARAM_STR);
        $stmt->bindParam(":bdate", $_POST["bdate"], PDO::PARAM_STR);
        $stmt->bindParam(":utype", $_POST["utype"], PDO::PARAM_STR);
    
     }

        $success = false;
        try {
            $actually_successful = $stmt->execute();

            $success = true;
        } catch (Exception $ex) {

        }
        if (!$actually_successful) {
            var_dump($stmt->errorInfo());
        }
        //Auto loader
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
        $mail->addAddress(strtolower($_POST["email"]));
        $mail->addReplyTo('info.tickify@gmail.com');
        $mail->isHTML(true);
        $mail->Subject="Your account at Tickify"; 
        $mail->Body ="Dear ".$_POST["firstname"]." ".$_POST["lastname"].","."<br><br>"."You have succesfully created a valid tickify account your username is ".$_POST["username"].".<br>"."Yours sincerely,<br>"."Your Tickify Team";
        $mail->Body .= "<br><a href=http://localhost:80/index.php><img src=\"cid:logo\" width=90 height=95 alt=Tickify/></a>";
        if(!$mail->send()){
            echo "mail was not sent";
        }else {
            echo "<script>console.log('The mail was sent!')</script>";
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
    <title>Create your account</title>
</head>
    
<body>   
     <form action="" method="post">
        <fieldset>
            <legend>Create your account</legend>

            <p><label class="title" for="username">Username</label>
            <input type="text" name="username" id="username"><br/>

            <label class="title" for="email">E-mail address</label>
            <input type="email" name="email" id="email"><br/>
            
            <label class="title" for="firstname">First name</label>
            <input type="text" name="firstname" id="firstname"><br/>

            <label class="title" for="minit">Minit</label>
            <input type="text" name="minit" id="minit"><br/>

            <label class="title" for="lastname">Last name</label>
            <input type="text" name="lastname" id="lastname"><br/>  

            <label class="title" for="bate">Birth date</label>
            <input type="date" name="bdate" id="bdate" placeholder="yyyy-mm-dd"><br/>            

            <label class="title" for="password">Password</label>
            <input type="password" name="password" id="password"><br/>

            <label class="title" for="password_repeat">Repeat password</label>
            <input type="password" name="password_repeat" id="password_repeat"><br/>

            <label class="title" for="postalcode">Postal code</label>
            <input type="text" name="postalcode" id="postalcode"><br/>

            <label class="title" for="houseno">House number</label>
            <input type="text" name="houseno" id="houseno"><br/>

            <label class="title" for="city">City</label>
            <input type="text" name="city" id="city"><br/>

            <label class="title" for="street">Street name</label>
            <input type="text" name="street" id="street"><br/>

            <label class="title" for="country">Country</label>
            <input type="text" name="country" id="country"><br/>

            <label class="title" for="sex">Sex</label>
            <input type="radio" name="sex" id="sex" value="m"/> M
            
            <input type="radio" name="sex" id="sex" value="f"/> F
            
            <input type="radio" name="sex" id="sex" value="o" checked="checked"/> O

            <label class="title" for="utype">User type</label>
            <select id="selector" name="utype">
                <option value="" disabled selected>Select</option>
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option></p>
            </select>
            
            <div class="submit"><input type="submit" value="Create account"></div> 

        </fieldset>
        <?php 
        $signupCheck = $_GET['signup'];

        if ($signupCheck == "succes") {
            echo "<fieldset id=errorbox><div class=alert><p class='errorsucces'>Your account has succesfully been created!</p></div></fieldset>";    
            exit();
        }
        
        elseif ($signupCheck == "missingusername") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No username was entered!</p></div></fieldset>";    
            exit();
        }
        
        elseif ($signupCheck == "missingpassword") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No password was entered!</p></div></fieldset>";    
            exit();
        }

        elseif ($signupCheck == "missingpasswordrepeat") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No password repeat was entered!</p></div></fieldset>";    
            exit();
        }

        elseif ($signupCheck == "passwordsnotsame") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>Passwords were not the same!</p></div></fieldset>";    
            exit();
        }

        elseif ($signupCheck == "missingcountry") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No password was entered!</p></div></fieldset>";    
            exit();
        }

        elseif ($signupCheck == "missingcity") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No city was entered!</p></div></fieldset>";    
            exit();
        }
        
        elseif ($signupCheck == "missingpostalcode") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No postalcode as entered!</p></div></fieldset>";    
            exit();
        
        }

        elseif ($signupCheck == "missingstreet") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No street was entered!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missinghousenr") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No house number was entered!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missingfirstname") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No first name was entered!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missinglastname") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No last name was entered!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missingsex") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No sex was given!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missingemail") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No email was entered!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missingbdate") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No birthdate was given!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "missingutype") {
            echo "<fieldset id=errorbox><div class=alert><p class='error'>No user type was given!</p></div></fieldset>";    
            exit();

        }

        elseif ($signupCheck == "datenotcorrect") {
            $birthdaydate = $_POST['bdate'];
            echo "<fieldset id=errorbox><div class=alert><p class='error'>Date can not be in the future $birthdaydate </p></div></fieldset>";
            exit();

        }       
        
        ?>            
    </form>
</body>
</html>