<?php
ob_start();
session_start();
include 'connect.php';

$event_name = $_GET['event_name'];

$sql1 = 'SELECT * from EVENT where EVENT_NAME=:event_name';
$sql2 = 'SELECT * from EVENT_REVIEW where EVENT_NAME=:event_name';
$sql3 = 'SELECT * from EVENT_TICKET_CATEGORY where event_name=:event_name';
$stmt = $conn->prepare($sql1);

$stmt->bindValue(':event_name', $event_name, PDO::PARAM_STR);
$stmt->execute();
$event = $stmt->fetch();

$review_statement = $conn->prepare($sql2);
$review_statement->bindValue(':event_name', $event_name, PDO::PARAM_STR);
$review_statement->execute();
#var_dump($event);

$category_statement = $conn->prepare($sql3);
$category_statement->bindValue(':event_name', $event_name, PDO::PARAM_STR);
$category_statement->execute();

if (isset($_GET['btAddReview'])) {


    $sql = '';
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':date', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindValue(':massage', $_POST['message'], PDO::PARAM_STR);
    $stmt->execute();
}

$sql4 = "SELECT image_id FROM images WHERE event_name = :event;";
$image_stmt = $conn->prepare($sql4);
$image_stmt->bindParam(':event', $event_name, PDO::PARAM_STR);
$image_stmt->execute();
$image = $image_stmt->fetch();
if(isset($_GET['btReview'])){
//    header('Location: /write_a_review.php?event_name='.urlencode($event_name));

    $path = "Location: write_a_review.php?".http_build_query(['event_name' => $event_name]);
    header($path);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tickify - Event Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/event_page.css">
    <link rel="stylesheet" type="text/css" href="css/footercss.css"/>
</head>
<body>
<main>
    <?php include 'header.php' ?>
    <!--    <article id="background">-->
    <!--        <img src="img/3.jpg" id="Background" width="100%" height="100%"/>-->

        <section id="event"><!--! Title Event -->
            <?php
                echo '<img src="uploads/'.$image[0].'" width = 950>';
            ?>

        </section>

    <aside id="information">
        <form>
            <fieldset>

                Event information:
                <div class="grid-container-info">
                    <div class="grid-item-info">
                        Event Name:
                    </div>
                    <div class="grid-item-info">
                        <label for="name"><?php echo "$event[event_name]" ?></label>
                    </div>
                    <div class="grid-item-info">
                        Location:
                    </div>
                    <div class="grid-item-info">
                        <label for="location"><?php echo "$event[location]" ?></label>
                    </div>
                    <div class="grid-item-info">
                        Start date:
                    </div>
                    <div class="grid-item-info">
                        <label for="start"><?php echo "$event[start_date]" ?></label> /
                        <label for="time"><?php echo "$event[start_time]" ?></label>
                    </div>
                    <div class="grid-item-info">
                        End date:
                    </div>
                    <div class="grid-item-info">
                        <label for="end"><?php echo "$event[end_date]" ?></label> /
                        <label for="time"><?php echo "$event[end_time]" ?></label>
                    </div>
                    <div class="grid-item-info">
                        Type of event:
                    </div>
                    <div class="grid-item-info">
                        <label for="date"><?php echo "$event[event_type]" ?></label>
                    </div>
                </div>
            </fieldset>
        </form>
    </aside>


    <aside id="categories">                                                <!-- Ticket Categories -->
        <form action=
              <?php if(isset($_SESSION['username'])){
                echo "shopping_cart.php";
              } else {echo "login.php";}?>
              method="post">
            <fieldset>
                Available Ticket Categories:

                    <button class="commitBt commit" name="btShoppingCart">Add to shoppingcart</button>

                <div class="grid-container">

                    <?php while ($category = $category_statement->fetch()): ?>
                        <div class="grid-item">
                            <label for="username"><?php echo "$category[category]" ?></label>
                        </div>
                        <div class="grid-item">
                            <input type="number" name="categories[<?php print $category['etc_id']; ?>]" value="0" min="0" max="10">
                            <input type="hidden" value = "<?php echo $event_name ?>" name = "event_name" >
                        </div>
                    <?php endwhile; ?>

                    <!--<div class="grid-item">
                        Category 1:
                    </div>
                    <div class="grid-item">
                        <input type="number" name="cat1" value="0" min="0" max="10">
                    </div>
                    <div class="grid-item">
                        Category 2:
                    </div>
                    <div class="grid-item">
                        <input type="number" name="cat2" value="0" min="0" max="10">
                    </div>
                    <div class="grid-item">
                        Category 3:
                    </div>
                    <div class="grid-item">
                        <input type="number" name="cat3" value="0" min="0" max="10">
                    </div>-->
                </div>
            </fieldset>
        </form>
    </aside>

    <center>
        <article id="description">                                            <!-- Ticket description -->
            <form>
                <fieldset>
                    <legend>Event description:</legend>
                    <label for="description" class="text"><?php echo "$event[event_description]" ?></label>
                </fieldset>
            </form>
        </article>

        <article id="review">                                                   <!--! Event Reviews -->
            <form method="get" action="">
                <input type="hidden" value="<?php print $event_name; ?>" name="event_name">
                <fieldset>

<!--                        <a href="write_a_review.php">Add review</a>-->
                    <div id="review_btn_container">
<!--                        <a href="--><?php //print "/write_a_review.php?event_name=".urlencode($event['event_name']); ?><!--">Add Review</a>-->
                        <label class="left">Reviews:</label>
                        <button class="commitBt commit" name="btReview">Add Review</button>
                    </div>



                    <?php while ($review = $review_statement->fetch()): ?>
                            <div class="grid-container-review">
                                <div class="grid-item-review-left"><label
                                            for="username"><?php echo "$review[username]" ?></label></div>
                                <div class="grid-item-review"><label for="date"><?php echo "$review[review_date]" ?></label>
                                </div>
                                <div class="grid-item-review"><label
                                            for="rate"><?php echo "$review[review_points]" ?></label></div>
                                <p></p>
                                <div class="grid-item-review-text"><?php echo "$review[review_text]" ?></div>
                            </div>
                    <?php $fetched=1; endwhile;?>

                    <?php
                    if(!$fetched == 1){
                        echo '<lable id="review_alt_text" > There are no reviews yet! Be the first to write a review! <br> <br> <br> </lable>';
                    }?>


                </fieldset>
            </form>

        </article>
    </center>
    <!--    </article>-->

        <?php include_once 'footer.php'?>

</main>
</body>
</html>
