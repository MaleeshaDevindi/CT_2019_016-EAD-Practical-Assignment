<?php

session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=notLoggedIn");
    exit;
}

header("Location: dashboard.php");
exit;

?>