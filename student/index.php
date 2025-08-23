<?php

session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

die("This is student dashboard");

?>