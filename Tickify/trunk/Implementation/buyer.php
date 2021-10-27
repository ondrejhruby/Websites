<?php
ob_start();
$errors = [];
require_once 'connect.php';
require_once 'header.php';

session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$stmt = $conn->prepare("select * from users where username=:username;");
$stmt->bindValue(':username', $username, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

function change_password($username, $password, $conn) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql5 = "UPDATE USERS set PASSWORD = :passhash where username = :username";
    $stmt5 = $conn->prepare($sql5);
    $stmt5->bindParam(":passhash", $password, PDO::PARAM_STR);
    $stmt5->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt5->execute();
}

if ($_POST) {

    if (isset($_POST['change'])) {
        try {
            if (!password_verify($_POST['current_password'], $user['password'])) {
                $errors = 'Invalid actual password!';
            } else {
                $password = $_POST['new_password'];
                change_password($user['username'], $password, $conn);
            }
        } catch (PDOException $ex) {
            
        }
    }
}

function check($username, $conn) {

    $sql90 = "SELECT user_order.order_id, amount FROM user_order INNER join order_item on user_order.order_id = order_item.order_id where username=:username";
    $stmt90 = $conn->prepare($sql90);
    $sql91 = "SELECT order_item.ticket_code FROM order_item INNER join ticket on order_item.ticket_code = ticket.ticket_code where order_item.ticket_code=:ticket_code;";
    $stmt91 = $conn->prepare($sql91);
    $sql92 = "SELECT event_ticket_category.etc_id, price, event_name, category FROM ticket INNER join event_ticket_category on ticket.id_category = event_ticket_category.etc_id where event_ticket_category.etc_id=:id;";
    $stmt92 = $conn->prepare($sql92);
   
    if ((!$conn->prepare($sql90)) && (!$conn->prepare($sql91)) && (!$conn->prepare($sql92))) {
        echo "Connection interrupted!";
        exit();
    } else {


        $stmt90->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt90->execute();
        $result = $stmt90->fetch();
        var_dump($result);
    
        $stmt91->bindParam(":ticket_code", $ticket_code, PDO::PARAM_STR);
        $stmt91->execute();
        $result = $stmt91->fetch();
    
        $stmt92->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt92->execute();
        $result = $stmt92->fetch();
    }
}

if ($_SERVER[`REQUEST_METHOD`] === `POST`) {
    if (isset($_POST['show'])) {
        $check = check($username, $conn);
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
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <legend> <?php print $user["username"]."'s page"; ?> </legend>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/form.css"> 
    </head>
    <body>
        <main> 


           
              
            <h1> <strong>Personal page Buyer</strong> </h1>  <br>
            <h3><strong>Personal Data: <br></strong></h3>
            Username: <?php echo $user["username"]; ?><br>
            First name/minit/last name: <?php echo "$user[fname] $user[minit] $user[lname]"; ?> <br>
            Sex: <?php echo $user["sex"]; ?>
            <br><br><br>

            <h3>Change password</h3> 
            <form method="post">
                <label for="password"> Fill in the actual password: </label> <br> <br> 
                <input type="password" name="current_password" id="current_password" maxlength="30"> 
                <br> 
                <label for="password"> Fill in the new password: </label> <br> <br> 
                <input type="password" name="new_password" id="password" maxlength="30"> 
                <br>
                <input type="submit" name="change" value="Submit"> 
            </form>
            <br>
            <br> 
            <h3> Bought tickets: </h3> 
            <form method="post"> 
                <input type="submit" name="show" value="Show"/>
<?php if (isset($check)) { ?>  
    <p><?php print htmlspecialchars($check["event_name"]); ?> <br> 
    <?php print htmlspecialchars($check["price"]); ?> <br>
    <?php print htmlspecialchars($check["tickets_amount"]); ?><br>
    <?php print htmlspecialchars($check["category"]); ?><br>
    <?php print htmlspecialchars($check["id"]); ?><br>
    <?php print htmlspecialchars($check["order_id"]); ?>
    <?php print htmlspecialchars($check["ticket_code"]); ?></p> 
<?php } ?>
      
    <h3>Delete this account:<h3/>
    <form method="post"> 
         <input type="submit" name="delete" value="Delete"/>
                </form>
                <footer> 


                </footer>  



        </main>    
    </body>
</html>
