<?php 
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
    

    <?php 
        $selector = $_GET["selector"];
        $validator = $_GET["validator"];

        if (empty($selector) || empty($validator)) {
            echo "Request cannot be validated";
        } 
        if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
        }
            ?>

            <form action="includes/create_new_password.inc.php" method="post">
                <fieldset>
                    <legend>Reset your password</legend>
                    <input type="hidden" name="selector" value="<?php echo $selector ?>">
                    <input type="hidden" name="validator" value="<?php echo $validator ?>">
                    <label class="title" for="password">Password</label>
                    <input type="password" name="password" id="password"><br/>
                    <label class="title" for="password_repeat">Repeat password</label>
                    <input type="password" name="password_repeat" id="password_repeat"><br/>
                    <button type="submit" name="password_reset_button">Reset Password</button>
                </fieldset>
            </form>
    </body>
</html>