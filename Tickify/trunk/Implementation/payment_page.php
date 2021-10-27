<?php
ob_start();
include './connect.php';

    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PAYMENT</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/payment_page.css">
    </head>
    <body>
        <main>
            <?php
            include 'header.php';
           ?>
            <article>
                
                <img class="background" src="img/3.jpg"/>

                <section>
                    <center><h1 style="font-size: 30px">Tickets</h1>

                    <div class="grid-container">
                        <div class="grid-item">
                            Event: 
                        </div>
                        <div class="grid-item">
                            No. Tickets: 
                        </div>
                        <div class="grid-item">
                            Price: 
                        </div>
                    </div>
                        <!--PHP-->
<!--                        <div class="grid-container">
                            <div class="grid-item1">
                                <?php echo $_POST[$cart_item['event_name']] ?>
                            </div>
                            <div class="grid-item2">
                                <?php echo $_POST[$cart_item['category']] ?>
                            </div>
                            <div class="grid-item1">
                              <?php echo $_POST[$cart_item['price']] ?>
                            </div>
                        </div>-->
                        
                </section>
                
                <aside>
                    
                    <center><h3>Please enter your payment data:</h3></center>
                    <p>Name:
                        <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['Fname']); ?>">
                    <a href="FILL IN LINK"></a></p>
                    <p>Surname:
                    <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['Lname']); ?>">
                    <a href="FILL IN LINK"></a></p>
                    <p>Adress:
                    <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['street']); ?>">
                    <a href="FILL IN LINK"></a></p>
                    <p>Postal Code:
                    <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['postal_code']); ?>">
                    <a href="FILL IN LINK"></a></p>
                    <p>City:
                    <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['city']); ?>">
                    <a href="FILL IN LINK"></a></p>
                    <p>Country:
                    <input type="text" id="Searchbar2" value="<?php echo isset($_SESSION['country']); ?>">
                    <a href="FILL IN LINK"></a></p>
<!--                    <p>Card Number:
                    <input type="text" id="Searchbar2">
                    <a href="FILL IN LINK"></a></p>
                    <p>Expiration date:
                    <input type="month" id="Searchbar2">
                    <a href="FILL IN LINK"></a></p>
                    <p>Validation Number (CCV):
                    <input type="text" id="Searchbar2">
                    <a href="FILL IN LINK"></a></p>
                    <img src="img/CVV.png" id="cvv"/>-->
                    <center><a href="FILL IN LINK"><button type="button">Send</button></a></center>
                    
                </aside>
            </article>
            
            <footer>
            </footer>
        </main>
    </body>
</html>
