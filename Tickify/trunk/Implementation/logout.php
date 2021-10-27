<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 21.11.18
 * Time: 14:36
 */
ob_start();
session_start();
include_once 'connect.php';
if (isset($_SESSION['username'])) {
    session_destroy();
    header('Location: /');
} else {
    header('Location: /login.php');
}
?>