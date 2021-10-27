<?php
    ob_start();
    include_once 'connect.php';

    session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $register_attempt = true;

    if (!$_SESSION['username']) {
        $errors[] = 'not logged in';
    }
 
    if (!$_POST['event_name']) {
        $errors[] = 'Missing event name!';
    }

    if (!$_POST['start_date']) {
        $errors[] = 'Missing start date!';
    }

    if ($_POST['start_date'] > $_POST['end_date']) {
        $errors[] = 'Start date cannot be later than the end date!';
    }

    if (!$_POST['start_time']) {
        $errors[] = 'Missing start time!';    
    }

    if (!$_POST['end_date']) {
        $errors[] = 'Missing end date!';
    }

    if (!$_POST['end_time']) {
        $errors[] = 'Missing end time!';    
    }

    if (!$_POST['event_type']) {
        $errors[] = 'Missing event type!';
    }

    if (!$_POST['location']) {
        $errors[] = 'Missing event location!';
    }

    if (!$_POST['event_name']) {
        $errors[] = 'Missing event description!';
    }  

    if (!$_POST['category']) {
        $errors[] = 'Missing event categories!';
    }

    if (!$_POST['tickets_amount']) {
        $errors[] = 'Missing amount of available tickets!';
    }

    if (!$_POST['price']) {
        $errors[] = 'Missing category price!';
    
    }
       
    if (count($errors) === 0 && isset($_SESSION['username'])) {    
    $sql = "insert into event(event_name, start_time, end_time, start_date, end_date , seller_name, event_type, location, event_description)values(:event_name, :start_time, :end_time, :start_date, :end_date, :seller_name, :event_type, :location, :event_description);";
    $sql2 = "insert into event_ticket_category (category, tickets_amount, price, event_name)values(:category, :tickets_amount, :price, :event_name);";
    $sql3 = "insert into event_ticket_category (category, tickets_amount, price, event_name)values(:category2, :tickets_amount2, :price2, :event_name);";
    $sql4 = "insert into event_ticket_category (category, tickets_amount, price, event_name)values(:category3, :tickets_amount3, :price3, :event_name);";     
    $stmt = $conn->prepare($sql); 
    $stmt2 = $conn->prepare($sql2);  
    $stmt3 = $conn->prepare($sql3);  
    $stmt4 = $conn->prepare($sql4);        

    $stmt->bindParam(":event_name", $_POST["event_name"], PDO::PARAM_STR);         
    $stmt->bindParam(":start_time", $_POST["start_time"], PDO::PARAM_STR);
    $stmt->bindParam(":end_time", $_POST["end_time"], PDO::PARAM_STR);
    $stmt->bindParam(":start_date", $_POST["start_date"], PDO::PARAM_STR);
    $stmt->bindParam(":end_date", $_POST["end_date"], PDO::PARAM_STR); 
    $stmt->bindParam(":seller_name", $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindParam(":event_type", $_POST["event_type"], PDO::PARAM_STR);

     // seller name from the $session
    $stmt->bindParam(":location", $_POST["location"], PDO::PARAM_STR);
    $stmt->bindParam(":event_description", $_POST["event_description"], PDO::PARAM_STR);

    // binding for the event_ticket_category 1
    $stmt2->bindParam(":event_name", $_POST["event_name"], PDO::PARAM_STR);
    $stmt2->bindParam(":category", $_POST["category"], PDO::PARAM_STR);
    $stmt2->bindParam(":tickets_amount", $_POST["tickets_amount"], PDO::PARAM_STR);
    $stmt2->bindParam(":price", $_POST["price"], PDO::PARAM_STR);
    // binding for the event_ticket_category 2
    $stmt3->bindParam(":event_name", $_POST["event_name"], PDO::PARAM_STR);
    $stmt3->bindParam(":category2", $_POST["category2"], PDO::PARAM_STR);
    $stmt3->bindParam(":tickets_amount2", $_POST["tickets_amount2"], PDO::PARAM_STR);
    $stmt3->bindParam(":price2", $_POST["price2"], PDO::PARAM_STR);
    // binding for the event_ticket_category 3
    $stmt4->bindParam(":event_name", $_POST["event_name"], PDO::PARAM_STR);
    $stmt4->bindParam(":category3", $_POST["category3"], PDO::PARAM_STR);
    $stmt4->bindParam(":tickets_amount3", $_POST["tickets_amount3"], PDO::PARAM_STR);
    $stmt4->bindParam(":price3", $_POST["price3"], PDO::PARAM_STR);

    // file uploading
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    
    // splitting the array to check file extension + lowercasing
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // allowed file extensions
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 10000000) {    
                $fileNameNew = uniqid('', true).".".$fileActualExt;   
                $fileDestination = 'uploads/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }            
    } else {
        echo "File extension not supported!";
    }         
        $success = false;
        try {
            $actually_successful = $stmt->execute();

            $sql3 = 'INSERT INTO IMAGES values(:event_name, :image_id);';                                               //insert image reference into the database
            $upload_statement = $conn->prepare($sql3);
            $upload_statement->bindValue(':event_name', $_POST['event_name'], PDO::PARAM_STR);
            $upload_statement->bindValue(':image_id', $fileNameNew, PDO::PARAM_STR);
            $upload_statement->execute();

            $success = true;
        } catch (Exception $ex) {

        }
        if (!$actually_successful) {
            var_dump($stmt->errorInfo());
        }

        $success2 = false;
        try {
            $actually_successful2 = $stmt2->execute();

            $success2 = true;
        } catch (Exception $ex) {

        }
        if (!$actually_successful2) {
            var_dump($stmt2->errorInfo());
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
    <title>Create your event</title>
</head>

<body>
    
      <?php  
      foreach($errors as $error): ?> 
    <p>
        <?php print $error;?>
    </p>
    
    <?php endforeach; ?>
    
    <form action="" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Create your event</legend>

            <p><label class="title" for="event_name">Event name </label>
                <input type="text" name="event_name" id="event_=name"> <br />

                <label class="title" for="location">Event location </label>
                <input type="text" name="location" id="location"> <br />

                <label class="title" for="start_date">Start date </label>
                <input type="date" name="start_date" id="start_date"> <br />

                <label class="title" for="end_date">End date </label>
                <input type="date" name="end_date" id="end_date"> <br />

                <label class="title" for="start_time">Start time </label>
                <input type="time" value="00:00" name="start_time" id="start_time"> <br />

                <label class="title" for="end_time">End time </label>
                <input type="time" value="00:00" name="end_time" id="end_time"> <br />

                <label class="title" for="event_type">Event type </label>
                <select id="selector" name="event_type">
                    <option value="" disabled selected>Select </option>
                    <option value="Amusement park">Amusement Park</option>
                    <option value="Concert">Concert</option>
                    <option value="Music festival">Music Festival</option>
                    <option value="Theatre play">Theatre Play</option>
                    <option value="Sports game">Sports Game</option>
                    <option value="Family">Family</option>
                    <option value="Other">Other</option>
                </select>
                <label class="title" for="event_description">Event description </label>
                <textarea placeholder="Describe your event!" name="event_description" rows="5" cols="34"></textarea>

                <label class="title" for="category">Event category 1 </label>
                <input type="text" name="category" id="category"> <br />
                 
                <label class="title" for="tickets_amount">Available ticket amount </label>
                <input type="text" name="tickets_amount" id="tickets_amount"> <br />
                
                <label class="title" for="price">Ticket price </label>
                <input type="text" name="price" id="category"> <br/>

                <label class="title" for="category2">Event category 2 </label>
                <input type="text" name="category2" id="category2"> <br />
                 
                <label class="title" for="tickets_amount2">Available ticket amount </label>
                <input type="text" name="tickets_amount2" id="tickets_amount2"> <br />
                
                <label class="title" for="price2">Ticket price </label>
                <input type="text" name="price2" id="category2"> <br/>

                <label class="title" for="category3">Event category 3 </label>
                <input type="text" name="category3" id="category3"> <br />
                 
                <label class="title" for="tickets_amount3">Available ticket amount </label>
                <input type="text" name="tickets_amount3" id="tickets_amount3"> <br />
                
                <label class="title" for="price3">Ticket price </label>
                <input type="text" name="price3" id="category3"> <br/>
                
                <label class="title" for="file">Event image</label>
                <input type="file" name="file">
                <div class="submit"><input type="submit" name="submit" value="Create event"></div>
                
        </fieldset>
    </form>
</body>

</html>