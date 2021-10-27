<?php ob_start(); 
include_once 'connect.php';

$stmt_getEventImage = $conn->prepare("SELECT * from event e inner join images i on e.event_name=i.event_name order by start_date;");
$stmt_getEventImage->execute();
$images = $stmt_getEventImage->fetchAll();
//Select the first 2 events (orderd by start_date).
//$images[row_nr][colomn_name]
$image_first = $images[0]['image_id'];
$image_second = $images[1]['image_id'];
$eventname_first = $images[0]['event_name'];
$eventname_second = $images[1]['event_name'];



?>
<!DOCTYPE html>

<html>
    <head>
        <title>TICKIFY</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/cssbritt.css">

    </head>
    <body>
        <main>
            <?php include 'header.php'?>

            <section>
                <a href="<?php print "/event_page.php?event_name=".urlencode($eventname_first); ?>"> 
                    <img class="top_margin" src="<?php echo "uploads/".$image_first ?>" width="960" alt="close_event"> 
                </a>          
                
                <a href="<?php print "/event_page.php?event_name=".urlencode($eventname_second); ?>"> 
                    <img class="top_margin" src="<?php echo "uploads/".$image_second ?>" width="960" alt="close_event"> 
                </a>
            </section>

           <footer>
            <?php include "footer.php"?>
            </footer>
        </main>
    </body>
</html>
