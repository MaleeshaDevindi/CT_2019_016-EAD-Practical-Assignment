<?php

session_start();
if ($_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit;
}

die("This is staff dashboard");

?>