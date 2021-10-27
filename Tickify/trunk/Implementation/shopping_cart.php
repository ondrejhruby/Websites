<?php
ob_start();
include 'connect.php';
                                                                                                                         //generate random String for Ticket code
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
session_start();
                                                                                                                         //Check if Eventname is set
if (isset($_POST['event_name'])) {
    $event = $_POST['event_name'];
}
if (!isset($_SESSION['tickets'])) {
    $_SESSION['tickets'] = array();
}
if (isset($_POST['btShoppingCart'])) {                                                                                   //and shoppingcart button is pressed

    $sql = 'SELECT * from EVENT_TICKET_CATEGORY where etc_id=:id';
    $stmt = $conn->prepare($sql);



    if (isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id => $amount) {                                                      //each category on the event site
            $amount = (int)$amount;                                                                                      //amount of tickets to put into the shoppingcart
            if ($amount === 0) {
                continue;
            }

            $stmt->bindValue(':id', $category_id, PDO::PARAM_STR);
            $stmt->execute();
            $category = $stmt->fetch();

            $sql10 = "SELECT end_date from event where event_name = '" . $category['event_name'] . "';";                 //check if event has not ended yet
            $stmt10 = $conn->prepare($sql10);
            $stmt10->execute();
            $event_date = $stmt10->fetch();

            $currentDateTime = date('Y-m-d');
            if($currentDateTime > $event_date[0]){
                $errors[] = 'Event '.$event.', allready has ended!';
                break;
            }

            $sql2 = 'SELECT count(id_category) as tickets_taken FROM TICKET where id_category = ' . $category_id . ' ;'; //calculate amount of tickets taken
            $statement_amount_taken = $conn->prepare($sql2);
            $statement_amount_taken->execute();

            $amount_taken = $statement_amount_taken->fetch()['tickets_taken'];

            $amount_available = $category['tickets_amount'] - $amount_taken;                                             //calculate available amount
                                                                                                                         //get the amount of tickets for this event already in s.cart and orders
            $sql_checkTicketsOwned1 = " select 		count(t.ticket_code) as tickets_in_cart                                                 
                                        from 		ticket t inner join shoppingcart_item s on s.ticket_code = t.ticket_code
                                        where       t.id_category = $category_id
                                        group by 	id_category;";
            $sql_checkTicketsOwned2 = " select 		count(t.ticket_code) as tickets_in_order
                                        from 		ticket t inner join order_item o on o.ticket_code = t.ticket_code
                                        where       t.id_category = $category_id
                                        group by 	id_category;";
            $statement_check_tickets1 = $conn->prepare($sql_checkTicketsOwned1);
            $statement_check_tickets2 = $conn->prepare($sql_checkTicketsOwned2);
            $statement_check_tickets1->execute();
            $statement_check_tickets2->execute();

            $tickets_in_cart = $statement_check_tickets1->fetch();
            $tickets_in_order = $statement_check_tickets2->fetch();
            $tickets_owned = $tickets_in_cart[0] + $tickets_in_order[0];
//            var_dump($tickets_in_cart);
//            var_dump($tickets_in_order);
//            var_dump($tickets_owned);

            if ($amount_available >= $amount and (int)$tickets_owned + $amount <= 10) {

                $sql3 = 'SELECT ticket_code FROM TICKET;';                                                               //query ticket codes, to give them a unique ID
                $statement_code = $conn->prepare($sql3);
                $statement_code->execute();

                $counter = 0;

                do {
                    $code = generateRandomString();

                    $sql3 = "SELECT ticket_code FROM TICKET where ticket_code = :code;";
                    $statement_code = $conn->prepare($sql3);
                    $statement_code->bindValue(':code', $code, PDO::PARAM_STR);
                    $statement_code->execute();

                    if (!$statement_code->fetch()) {
                        $sql_ticket_relation = "INSERT into TICKET values(:code, :category_id);";
                        $ticket_statement = $conn->prepare($sql_ticket_relation);
                        $ticket_statement->bindValue(':code', $code, PDO::PARAM_STR);
                        $ticket_statement->bindValue(':category_id', $category_id, PDO::PARAM_STR);
                        $ticket_statement->execute();
                        $_SESSION['tickets'][] = $code;
                        $counter++;

                        $sql_shoppingcart_query = "SELECT * from shoppingcart where username = :username;";
                        $statement_shoppingcart = $conn->prepare($sql_shoppingcart_query);
                        $statement_shoppingcart->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
                        $statement_shoppingcart->execute();

                        if (!$statement_shoppingcart->fetch()){
                            $sql_shoppingcart_insert = "INSERT into shoppingcart(username) values(:username);";
                            $statement_shoppingcart_insert = $conn->prepare($sql_shoppingcart_insert);
                            $statement_shoppingcart_insert->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
                            $statement_shoppingcart_insert->execute();
                        }

                        $statement_shoppingcart->execute();
                        $shoppingcart = $statement_shoppingcart->fetch();
                        //var_dump($shoppingcart);
                        $sql_shoppingcart_item = "INSERT into SHOPPINGCART_ITEM values (:cart, :code)";
                        $statement_shoppingcart_item = $conn->prepare($sql_shoppingcart_item);
                        $statement_shoppingcart_item->bindValue(':code', $code, PDO::PARAM_STR);
                        $statement_shoppingcart_item->bindValue(':cart', $shoppingcart['cart_id'], PDO::PARAM_STR);
                        $statement_shoppingcart_item->execute();


                    }
                } while ($counter < $amount);

//                if ($amount >= ($amount_taken)) {
//                    $sql_ticket_relation = 'INSERT into TICKET values(' . generateRandomString() . ', ' . $category_id . ');';
//                }
            } else {
                $errors[] = 'Maximum of tickets reached for event ' . $event . '!';
            }
        };
    }
    //Calculate bought tickets

}

if(isset($_POST['delete'])){
    $sql9 = "delete from ticket where ticket_code = :code;";
    $stmt9 = $conn->prepare($sql9);
    $stmt9->bindParam('code', $_POST['ticket_code'], PDO::PARAM_STR);
    $stmt9->execute();
//    var_dump($_POST['ticket_code']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/shopping_cart.css">
</head>
<body>
<main>
<?php include 'header.php';?>
        <section id="content">
            <label class = 'error'>
                <?php
                if(!$errors == null){
                    foreach ($errors as $error){
                        echo $error;
                    }
                }
                ?>
            </label>
             <div class="grid-container">
                <div class="grid-item1 bold">
                    Event:
                </div>
                <div class="grid-item2 bold">
                    Category:
                </div>
                <div class="grid-item1 bold">
                    Price:
                </div>
                 <div class="grid-item2 bold">
                    Delete:
                </div>
            </div>

            <?php

            $sql8 = "SELECT * from shoppingcart where username = :username;";
            $stmt8 = $conn->prepare($sql8);
            $stmt8->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
            $stmt8->execute();
            $shoppingcart = $stmt8->fetch();

            $sql7 = "SELECT * from shoppingcart_item where cart_id = :cart;";
            $stmt7 = $conn->prepare($sql7);
            $stmt7->bindParam(':cart', $shoppingcart['cart_id'], PDO::PARAM_STR);
            $stmt7->execute();
            $tickets = $stmt7->fetchAll();


            foreach ($tickets as $ticket):
                $sql5 = "select 	category, price, event_name
                         from 		event_ticket_category inner join ticket on etc_id = id_category
                         where 		ticket_code = :code;";
                $cart_statement = $conn->prepare($sql5);
                $cart_statement->bindValue(':code', $ticket['ticket_code'], PDO::PARAM_STR);
                $cart_statement->execute();
                $cart_item = $cart_statement->fetch();
                $global_tickets[] = $sql5;
                $_POST['total_to_pay'] = $_POST['total_to_pay'] + $cart_item['price']?>

                <form action="" method="post">
                    <div class="grid-container">
                        <div class="grid-item1">

                            <a href="<?php print "/event_page.php?event_name=".urlencode($cart_item['event_name']); ?>">
                                <?php print htmlspecialchars($cart_item['event_name']); ?>
                            </a>

<!--                            --><?php //echo $cart_item['event_name'] ?>
                        </div>
                        <div class="grid-item2">
                            <?php echo $cart_item['category'] ?>
                        </div>

                        <div class="grid-item1">
                            <?php echo $cart_item['price'] ?>
                        </div>
                        <div class="grid-item2">
                            <button name="delete">delete</button>
                            <input type = hidden value="<?php echo $ticket['ticket_code'] ?>" name = "ticket_code">
                        </div>
                    </div>
                </form>
            <?php endforeach; var_dump($_POST['total_to_pay']);?>

        </section>
        <aside>
            <form method="post" action="payment_page.php">
                <table>
                    <tr>
                        <th><h1> TOTAL TO PAY</h1></th>
                    </tr>
                    <tr>
                        <td><h3> <?php echo $_POST['total_to_pay'] ?> €</h3></td>
                    </tr>
                </table>

                <button class="paynow"> PAY NOW! </button>
            </form>

        </aside>


<!--    --><?php //include_once 'footer.php '?>

</main>
</body>
</html>
