<?php
if (isset($_GET['search_term'])) {
}
ob_start();

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
?><!DOCTYPE html>
<html>
<head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/headerGuus.css">

</head>
<body>
<header>
    <div id="logo_container">
        <a href="index.php"><img src="img/Logo-white.png" id="logo" alt="Logo"></a>
    </div>
    <div id="login_container">
        
            <a class="next" href=<?php
        if(isset($_SESSION['username'])){
            echo "<h3>".$username."</h3>";
            echo 'buyer.php';
        } else {
            echo "login.php";
            print "<h3>".$username."</h3>";
            
        }?>

            <?php
        if(isset($_SESSION['username'])){
            echo "<h3>".$username."</h3>";
        } else {
            print "<h3>".$_SESSION['username']."</h3>";
        }
        ?>
                <span class="icon-wrap"><img src="img/makefg.png" width="30"/></span>
                
         
            
        </a>
    
    </div>
</header>
</body>


