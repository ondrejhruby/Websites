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
        
        }
        
            
        }
    }
    function check($category_id, $conn) {
        $sql3 = "SELECT event_name from EVENT WHERE seller_name =".$_SESSION['username'].";";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute();
        $events = $stmt3->fetch();

        foreach ($events as $event){
            $sql4 = "SELECT etc_id FROM event_ticket_category WHERE event_name = $event;";
            $stmt4 = $conn-> prepare($sql4);
            $stmt4->execute();
            $event = $stmt4->fetch();

            foreach ($event as $category_id){
                $sql5 = 'SELECT count(id_category) as tickets_taken FROM TICKET where id_category = ' . $category_id . ' ;';
                $statement_amount_taken = $conn->prepare($sql5);
                $statement_amount_taken->execute();
                $amount_taken = $statement_amount_taken->fetch()['tickets_taken'];
            }
            echo $amount_taken; 


            }
        }


/*
        $sql64 = "SELECT id_category FROM event_ticket_category INNER join ticket on  event_ticket_category.etc_id=event.id_category;";
        $stm64->bindParam(":id_category", ":etc_id", PDO::PARAM_STR);
//        $stmt6 = $conn-> prepare($sql66);
//        $stmt6->execute();
        $stmt64 = $conn-> prepare($sql64);
        $stmt64->execute();
        $sql2 = 'SELECT count(id_category) as tickets_taken FROM TICKET where id_category = ' . $category_id . ' ;';  
        $statement_amount_taken = $conn->prepare($sql2);
        $statement_amount_taken->execute();
        $amount_taken = $statement_amount_taken->fetch()['tickets_taken'];
        return $amount_taken; 
      */  
     
    if ($_POST) {
        if(isset($_POST['show'])) { 
           $check = check($category_id, $conn);
            
            
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
        <title>Seller page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/form.css"> 
    </head>
    <body>
        <form action="" method="post">
            <fieldset>
                <legend> <?php print $user["username"]."'s page"; ?> </legend>

                    <h2>Personal Data</h2>

                    <label class="title">Username </label>
                    <?php echo $user["username"]; ?>

                    <br/>     

                    <label class="title" for="names">Name </label>
                    <?php echo "$user[fname] $user[minit] $user[lname]";?>    

                    <br/>

                    <label class="title" for="sex">Sex </label>
                    <?php if ($user["sex"] == 'f' || $user["sex"] == 'F') { 
                        echo "Female";
                    } else if ($user["sex"] == 'm' || $user["sex"] == 'M') {
                        echo "Male";
                    } else if ($user["sex"] == 'o' || $user["sex"] == 'O') {
                        echo "Other";
                    }  

                    ?>

                    <br/>     

                <h4>Change password</h4>        

                    <label class="title" for="current_password">Fill in current password </label>
                    <input type="password" name="current_password" id="current_password" maxlength="30">  

                    <label class="title" for="new_password">Fill in new password </label>
                    <input type="password" name="new_password" id="new_password">          

                    <input type="submit" name="change" value="Change">   
                    
                    <h3> Listed tickets: </h3> 
                    <form method="post"> 
                    <input type="submit" name="show" value="Show"/> 
                    <?php if (isset($check)){ ?>  
                    <p><?php print htmlspecialchars($check["event_name"]);?> <br> 
                        <?php print htmlspecialchars($check["price"]); ?> <br>
                            <?php  print htmlspecialchars($check["tickets_amount"]); ?><br></p>
                    <?php } ?>
                    
                    <h3>Delete this account:<h3/>
                    <form method="post"> 
                        <input type="submit" name="delete" value="Delete"/>
                </form>
            </fieldset>
        </form>
    </body>
</html>
