<?php
ob_start();
    $errors = [];
    require_once 'connect.php';
    require_once 'header.php'; 
    
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];    
}

    $stmt = $conn->prepare("select * from users where username=:username;");
    $stmt->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    function change_password($username, $password, $conn) {
	$password = password_hash($password, PASSWORD_DEFAULT);
	$sql = "UPDATE USERS set PASSWORD = :passhash where username = :username";
	$stmt5 = $conn->prepare($sql);
    	$stmt5->bindParam(":passhash", $password, PDO::PARAM_STR);
    	$stmt5->bindParam(":username", $username, PDO::PARAM_STR);
   	$stmt5->execute();
	
    }

    if($_POST) {        
        if(isset($_POST['change'])) {
            try {
                if(!password_verify($_POST['current_password'], $user['password'])) {
                    $errors = 'Invalid current password!';
                } else {
                    $password = $_POST['new_password'];
                    change_password($user['username'], $password, $conn);
                    header("Location:login.php");
                    
                    exit();
                }
            } catch (PDOException $ex) {     
        }}     
    }

    function search_user($username, $conn) {
    $sql2 = "SELECT * FROM users WHERE username = :username;"; 
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt2->execute();
    return $stmt2->fetch();

    }

    if ($_POST) {
        if(isset($_POST['Search'])){
            $username = $_POST['username'];
            $searched_user = search_user($username, $conn);

        }

    function search_review($event_name, $conn) {
    $sql3 = "SELECT * FROM event_review WHERE event_name =:event_name;"; 
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bindParam(":event_name", $event_name, PDO::PARAM_STR);
    $stmt3->execute();
    return $stmt3->fetch(); 

    }

    if ($_POST) {
        if(isset($_POST['review'])){
            $event_name = $_POST['event_name'];
            $searched_review = search_review($event_name, $conn);
        }
    }    
    if ($_POST) {
            if(isset($_POST['removereview']) && (isset($_POST['event_name']))) { 
                $event_name = $_POST['event_name'];  
                $sql4 = " DELETE FROM event_review WHERE event_name =:event_name;";
                $stmt4 = $conn->prepare($sql4);
                $stmt4->bindValue(':event_name', $event_name, PDO::PARAM_STR);
                $stmt4->execute();                 
            }
        }
    } 
    function delete($username, $conn) {
    $stmt9 = $conn->prepare("delete from users where username=:username;");
    $stmt9->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt9->execute();
    
}
if ($_SERVER[`REQUEST_METHOD`] === `POST`) {
    if (isset($_POST['delete'])) {
        $delete = delete($username, $conn);
        session_destroy();
    }
} 
    
        

         
   
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Administrator page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/form.css">
    </head>
    <body>
        <form action="" method="post">     
            <fieldset>
                <legend> <?php print $user["username"]."'s page"; ?> </legend>

                <h2>Personal Data</h2>

                <label class="title">Username: </label>
                <?php echo $user["username"]; ?>

                <br/>    

                <label class="title" for="names">Name: </label>
                <?php echo "$user[fname] $user[minit] $user[lname]";?>    

                <br/>

                <label class="title" for="sex">Sex: </label>
                <?php if ($user["sex"] == 'f' || $user["sex"] == 'F') { 
                    echo "Female";
                } else if ($user["sex"] == 'm' || $user["sex"] == 'M') {
                    echo "Male";
                } else if ($user["sex"] == 'o' || $user["sex"] == 'O') {
                    echo "Other";
                }      

                ?>

                <br/>     

            <h4>Change password:</h4>         
        
                <label for="password"> Fill in the actual password: </label> <br> <br> 
                <input type="password" name="act_password" id="act_password" maxlength="30"> 
                 <br> 
                <label for="password"> Fill in the new password: </label> <br> <br> 
                <input type="password" name="new_password" id="password" maxlength="30"> 
                 <br>
                <input type="submit" name="change" value="Submit"> 
            
            <br>
            <br> 
            <h2> Administrations: </h2> <br> 
            <h4>Remove a review:</h4>
           

            
             
            <label class="title">Search for event name</label>
           
            <input name="event_name" type="text"><input type="submit" name="review" class="button" value="Search">
                
            <?php if (isset($searched_review)){ ?>

                    <h4>Search results</h4>

                <label class="title">Review ID </label>
                <?php print htmlspecialchars($searched_review["review_id"]); ?> <br>    

                <label class="title">Event name </label> 
                <?php print htmlspecialchars($searched_review["event_name"]); ?> <br>

                <label class="title">Username </label>
                <?php print htmlspecialchars($searched_review["username"]); ?> <br> 

                <label class="title">Review date </label> 
                <?php print htmlspecialchars($searched_review["review_date"]); ?> <br>

                <label class="title">Review points given </label>
                <?php  print htmlspecialchars($searched_review["review_points"]); ?> <br>

                <label class="title">Event review </label>
                <textarea name="review_text" disabled>                                    
                <?php print htmlspecialchars($searched_review["review_text"]); ?>      
                </textarea>
                                                                           
                <input type="submit"  name="removereview" value="Remove review">

            <?php } ?>         

            <h4>Remove a user:</h4>
            
            <label class="title">Search on username</label>
            
            <input type="text" name="username" /> <input type="submit" class="button" name="Search" value="Search"/> 

            <?php if (isset($searched_user)){ ?>
                
                <h4>Search results</h4>
                <label class="title">Username</label>
                <?php print htmlspecialchars($searched_user["username"]); ?> <br>

                <label class="title">First name</label>
                <?php print htmlspecialchars($searched_user["fname"]); ?> <br>

                <label class="title">Minit</label>
                <?php print htmlspecialchars($searched_user["minit"]); ?> <br>
                
                <label class="title">Last name</label>
                <?php print htmlspecialchars($searched_user["lname"]); ?>

                <br>
                
                <a href="remove_user.php?username=<?php print urlencode($searched_user["username"]); ?>">Remove user</a>
           
            <?php } ?>           
                
            </fieldset>
        </form> 
    </body>
</html>
