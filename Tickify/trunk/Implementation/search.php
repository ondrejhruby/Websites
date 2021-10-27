<?php
include 'connect.php';
function search_user($username) {
    $sql = "SELECT  * FROM users WHERE (username, fname, minit, lname)values(:username, :firstname, :minit, :lastname);";
    echo($username && $firstname && $minit && $lastname);
}

?>