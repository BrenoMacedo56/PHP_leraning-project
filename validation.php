<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

