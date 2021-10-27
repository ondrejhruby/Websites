<?php
ob_start();
$event_name = $_GET['event_name'];

session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

/*if(isset($_GET['event_name'])) {
    $sql1 = 'INSERT INTO EVENT_REVIEW(review_date, review_text) VALUES(:date, :message)';
    $stmt = $conn->prepare($sql1);
}*/

if (isset($_POST['btSubmit']) && isset($_POST['date']) && isset($_POST['message']) && isset($_GET['event_name']) && isset($username) && isset($_POST['points'])) {
    include 'connect.php';
    
    $sql = 'INSERT INTO EVENT_REVIEW(review_date, event_name, review_text, username, review_points) 
            VALUES(:date, :event_name, :message, :username, :points)';
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':date', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindValue(':message', $_POST['message'], PDO::PARAM_STR);
    $stmt->bindValue(':event_name', $_GET['event_name'], PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':points', $_POST['points'], PDO::PARAM_STR);
    $stmt->execute();

    header('Location: /event_page.php?event_name='.urlencode($event_name));
} else if(!isset($_SESSION['username'])) {
    $errors[] = 'You are not logged in!';
} else if(isset($_POST['btSubmit'])){
    $errors[] = 'fill in all inputs please!';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Write a Review</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/write_a_review.css"/>
    </head>
    <body>
        <main>
            <?php include_once 'header.php'?>
            <section>
                <form class="grid-container" method="post" action="">
                    <div class="grid-item-left">Rate the Event:</div>
                    <div class="grid-item-left">

                        <select class="dropdown" name="points">
                            <option value=""  disabled selected>Select review points: </option>
                            <option name="points" value="0">0</option>
                            <option name="points" value="1">1</option>
                            <option name="points" value="2">2</option>
                            <option name="points" value="3">3</option>
                            <option name="points" value="4">4</option>
                            <option name="points" value="5">5</option>
                        </select>
                    </div>
                    <div class="grid-item-left">Date of Visit: </div>
                    <div class="grid-item-left"> <input type="date" name="date"> </div>
                    <div class="grid-item-left">Write a review:</div>
                    <div class="grid-item-left"> <textarea rows="7" cols="80" name="message"></textarea></div>
                    <div class="grid-item">
                        <?php if($errors) { foreach ($errors as $error): ?>
                            <?php echo $error?> <br>
                        <?php endforeach; }?>
                    </div>
                    <div class="grid-item-left">
                        <button name="btSubmit" value="Submit" id="submit-btn">
                            Submit
                        </button>
                    </div>
                    <!--<a href="<?php /*print "/event_page.php?event_name=".urlencode($event_name); */?>">
                        add review
                    </a>-->

                    <!--<button name="btSubmit" value="Submit" id="submit-btn">
                        Submit
                    </button>-->
                </form>
            </section>
            <footer><?php include_once 'footer.php' ?></footer>
        </main>
    </body>
</html>
