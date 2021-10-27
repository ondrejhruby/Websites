<?php

ob_start();
//session_start();
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $sql = "SELECT type_user from users where username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $type = $stmt->fetch();
}

if(isset($_GET['type'])){
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if($url){}
//            header('Location: searchingpage.php?'.http_build_query(['type' => $_GET['type']]));
//        var_dump('test');
//        header('Location: searchingpage.php');

}
?><!DOCTYPE html>
<html>
<head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/newheader.css">
</head>
<body>
<header>
    <?php
        if(isset($_SESSION['username'])){
            echo '<div id="logout_container">
                    <a href="logout.php">
                        <img src="img/logout.png" width="25">
                    </a>
                  </div>';
        }
    ?>
    <div id="logo_container">
        <a href="index.php"><img src="img/Logo-white.png" id="logo" alt="Logo"></a>
    </div>
    <div id="login_container">
        <a class="link" href=<?php
        if(isset($_SESSION['username'])){
            if($type[0] == 'buyer'){
                echo 'buyer.php';
            } else if($type[0] == 'seller'){
                echo 'seller.php';
            } else if($type[0] == 'admin'){
                echo 'administrator.php';
            }

        } else {
            echo 'login.php';
        }?>>

            <?php
        if(isset($_SESSION['username'])){
            echo $_SESSION['username'];
        } else {
            echo 'Log In';
        }?>
            <img src="img/makefg.png" width="20"/>
        </a>
    </div>
</header>
<nav>
    <?php
    /*                    echo '<a href="index.php"> <button type="button"><img src="img/Logo-white.png" id="logo" width="40px" height="45px"/></button></a>';
                        echo '<a href="FILL IN LINK"><button type="button">Sports</button></a>';
                        echo '<a href="FILL IN LINK"><button type="button">Concerts</button></a>';
                        echo '<a href="FILL IN LINK"><button type="button">Theater</button></a>';
                        echo '<a href="FILL IN LINK"><button type="button">Festivals</button></a>';
                        echo '<a href="FILL IN LINK"><button type="button">Other</button></a>';

    //                  <!-- account box "username" -->
                        echo '<a href="FILL IN LINK"><img src="img/makefg.png" id="Profile" width="20"/></a>';
                        echo '<em id="Username">Username</em>';
    //                  <!-- searching -->
                        echo '<input type="text" id="Searchbar">';
                        echo '<a href="FILL IN LINK"><img src="img/magnifier.png" id="Magnifier" width="15"/></a>';
    //                  <!-- Shoppingcard -->
                        echo '<a href="shopping_cart.php"><img src="img/cart-icon-white.png" id="ShoppingCart" width="20"/></a>';*/ ?>
    <div id="nav">
            <form method="get" action="searchingpage.php">
                <ul>
                    <li><button class="headerBt" name="type" value="Sports game">Sports</button></li>
                    <li><button class="headerBt" name="type" value="Concerts">Concerts</button></li>
                    <li><button class="headerBt" name="type" value="theatre">Theatre</button></li>
                    <li><button class="headerBt" name="type" value="Music festival">Festivals</button></li>
                    <li><button class="headerBt" name="type" value="Family">Family</button></li>
                    <li><button class="headerBt" name="type" value="Other">Other</button></li>
                    <li class="right"><a class="cart" href=<?php
                        if(isset($_SESSION['username'])){
                            echo 'shopping_cart.php';
                        } else {
                            echo 'login.php';
                        }
                        ?>>
                            <img src="img/cart-icon-white.png" width="20"> </a></li>
                    <!--<li class="search">
                        <a href="searchingpage.php">Search!</a></li>-->
                    <div class="searchdiv right">
                        <input name="search_term" type="search" placeholder="Enter search term" value="<?php print $_GET['search_term']; ?>">
                    </div>
                </ul>
            </form>
    </div>


</nav>
</body>
</html>
