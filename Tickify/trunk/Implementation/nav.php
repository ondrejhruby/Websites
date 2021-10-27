<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/nav.css">
    <title>Document</title>
</head>
<body>
    <nav>
        <form method="get" id="search_bar" action="searchingpage.php">
            <ul>
                <li><a href="#">test</a></li>
                <li><a href="#">test</a></li>
                <li><a href="#">test</a></li>
                <li><a href="#">test</a></li>
                <li><a href="#">test</a></li>

            

                <div class="searchdiv">
                <input name="search_term" type="search" placeholder="Enter search term" value="<?php print $_GET['search_term']; ?>">
            </div>
            
                
            </ul>


                
        </form>
    </nav>    
</body>
</html>