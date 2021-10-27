<?php

ob_start();
$host = "prj1_postgres";
//$host = "localhost";
//$host = "00_prj1_dbs_1";
$port = "5432";
$db = "tickify";
$user = "postgres";
$pword = "mypassword";
$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pword";

try { // if the connection doesn’t work do not exit but go to catch part
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn) {
        //echo "Connected to the <strong>$db</strong> database successfully!";
    }
} catch (PDOException $e) { // report error message
    echo $e->getMessage();
}
?>