<?php
    ob_start();
    include 'connect.php';
    //filter on type only
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }
    else {
        $type = "";
    }
    
    
    //Filter on search term
    if (isset($_GET['search_term'])) {
        $search_term = $_GET['search_term'];
    } else {
        $search_term = "";
    }

    //filter price
    if (isset($_GET['rValue'])) {
        $rangeValue = $_GET['rValue'];
    } else {
        $rangeValue = 1000;
    }

    //Filter month
    if (!empty($_GET['month'])) {
        $events_of_month_sql = "AND EXTRACT(MONTH FROM start_date) in (";

        //add every month selected to the search
        foreach ($_GET['month'] as $month) {
            if (!is_numeric($month)) {
                continue;
            }
            $events_of_month_sql .= $month.",";
        }
        $events_of_month_sql = rtrim($events_of_month_sql, ',').');';
    }
    else {
        $events_of_month_sql = "";
    }
    


    //Filter functions: Search + Price, needs to be in one form! So, now it's in one sql_query
    $search_term = "%$search_term%";
    $stmt = $conn->prepare($sql = "SELECT *
                                    FROM event e 
                                    WHERE e.event_name ILIKE :name
                                    AND (
                                        select min(etc.price)
                                        from EVENT_TICKET_CATEGORY etc
                                        where etc.event_name=e.event_name
                                        group by etc.event_name
                                    ) < :minimumprice 
                                    $events_of_month_sql ;");
    
    $stmt->bindParam(':name', $search_term, PDO::PARAM_STR);
    $stmt->bindParam(':minimumprice', $rangeValue, PDO::PARAM_INT);
    

    $stmt->execute();
    //$events = $stmt->fetchAll($sql_event); >> See if-statement below
    
    //Show the lowest price on the searchingpage
    $stmt2_min_price = $conn->prepare("SELECT min(price) as minimum_price
                                        from EVENT_TICKET_CATEGORY
                                            where event_name=:event_name
                                            group by EVENT_NAME");

    //Show the right image
    $stmt_result_image = $conn->prepare("SELECT image_id as image_id from images where event_name=:event_name");

    //SQL search: filter on type only
    $stmt_filterOnType = $conn->prepare("SELECT * FROM event e WHERE e.event_type= :type");
    $stmt_filterOnType->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt_filterOnType->execute();
    if (isset($_GET['type'])) {
        $events = $stmt_filterOnType->fetchAll();
    } else {
       $events = $stmt->fetchAll();
    }



?>

<!DOCTYPE html>

<html>
    <head>
        <title>Search</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/cssbritt.css">
       <!-- <link rel="stylesheet" type="text/css" href="css/form.css"> -->

    </head>
    <body>
        <main>
            <?php include 'header.php' ?>
            <form id="total_search">
                
                <aside class="toSide">
                    <h1> Filters </h1>
                   
                    <!-- price rangeslider -->
                    <input type="range" name="rValue" min="1" max="101" value="<?php echo $_GET['rValue']; ?>" step="10">
                    <?php echo $_GET['rValue']; ?>
                    <br>
                        
                    <!-- filter on month -->
                        <input type="checkbox" name="month[]" value=01 > January <br>
                        
                        <input type="checkbox" name="month[]" value=02 > February <br>
                        
                        <input type="checkbox" name="month[]" value=03 > March <br>
                    
                        <input type="checkbox" name="month[]" value=04 > April <br>
                    
                        <input type="checkbox" name="month[]" value=05 > Mai <br>
                    
                        <input type="checkbox" name="month[]" value=06 > June <br>
                    
                        <input type="checkbox" name="month[]" value=07 > July <br>
                    
                        <input type="checkbox" name="month[]" value=08 > August <br>
                    
                        <input type="checkbox" name="month[]" value=09 > September <br>
                    
                        <input type="checkbox" name="month[]" value=10 > October <br>
                    
                        <input type="checkbox" name="month[]" value=11 > November <br>
                    
                        <input type="checkbox" name="month[]" value=12 > December
                        
                        <input type="submit" name="submit_button" value="Filter results!" >
                        

                </aside>
                <!-- submit your searchfilters -->
                
            </form>

            <!-- <article> -->
                <!-- sort by?? -->
                <?php foreach ($events as $event): ?>

                    <?php
                        //Checks at what $event we currently at, matches that to the query $stmt2_min_price, so we have the right minimum_price.
                        $stmt2_min_price->bindParam(":event_name", $event["event_name"], PDO::PARAM_STR);
                        $stmt2_min_price->execute();
                        $min_price_result = $stmt2_min_price->fetch();
                        $min_price = $min_price_result["minimum_price"];

                        //Checks at what $event we currently at, matches that to the query $stmt_result_image, so we have the right image.
                        $stmt_result_image->bindParam(":event_name", $event["event_name"], PDO::PARAM_STR);
                        $stmt_result_image->execute();
                        $result_image = $stmt_result_image->fetch();
                        $image_id = $result_image["image_id"];

                    ?>

                    <article class="grid-container_event">

                        <!-- image -->
                        <img class="event_griditem eventpic_griditem " src="<?php echo'uploads/'.$image_id ?>" width="160" height="250" alt="event_picture">

                        <!-- title -->
                        <h3 class=event_griditem>
                            <a href="<?php print "/event_page.php?event_name=".urlencode($event['event_name']); ?>">
                                <?php print htmlspecialchars($event['event_name']); ?>
                            </a>
                        </h3>
                        
                        <!-- min_price -->
                       <form class="event_gridbutton" >
                            <button class="article_button"> <p> <?php echo $min_price ?>  </p> </button>
                       </form>

                        <!-- eventdescription -->
                        <p class="event_griditem"> <?php echo $event['event_description'] ?>  </p>

                        <!-- more info -->
                        <form class="event_gridbutton" action="event_page.php">
                            <button class="article_button" type="submit"> More info </button>
                            <input type='hidden' name='event_name' value="<?php echo $event['event_name'] ?>">
                        </form>
                        

                    </article>
                <?php endforeach; ?>
           <!-- </article> -->
           <?php include 'footer.php' ?>

        </main>
    </body>
</html>
