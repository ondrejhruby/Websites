<?php
    ob_start();
    //session_start();
    if(!isset($_SESSION)){ 
        session_start();} 
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];}
    if(isset($_GET['type'])){
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        if($url){}}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Header</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/mewheader.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<header>
    <div id="logo_container"><a href="index.php"><img src="img/Logo-white.png" id="logo" alt="Logo"></a></div>
    <div id="login_container"><a class="link" href=
        <?php
        if(isset($_SESSION['username'])){
            echo 'buyer.php';} 
        else{
            echo 'login.php';}?>>
        <?php
        if(isset($_SESSION['username'])){
            echo $_SESSION['username'];} 
        else{
            echo 'Log In';}?>
            <img src="img/makefg.png" width="20"/></a></div>
</header>
<nav>
    <form method="get" action="searchingpage.php">
        <ul>
            <li><button class="headerBt" name="type" value="sports">Sports</button></li>
            <li><button class="headerBt" name="type" value="concerts">Concerts</button></li>
            <li><button class="headerBt" name="type" value="theatre">Theatre</button></li>
            <li><button class="headerBt" name="type" value="festivals">Festivals</button></li>
            <li><button class="headerBt" name="type" value="family">Family</button></li>
            <li><button class="headerBt" name="type" value="other">Other</button></li>
            <li class="right"><a class="cart" href=
                <?php
                if(isset($_SESSION['username'])){
                    echo 'shopping_cart.php';} 
                else{
                    echo 'login.php';}?>>
                    <img src="img/cart-icon-white.png" width="20"></a></li>
            <div class="searchdiv right">
                <input name="search_term" type="search" placeholder="Enter search term" value="
                <?php print $_GET['search_term']; ?>">
            </div>
        </ul>
    </form>
</nav>
</body>
</html>
