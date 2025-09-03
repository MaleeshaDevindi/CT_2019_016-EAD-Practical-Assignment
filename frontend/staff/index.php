<?php

session_start();
if ($_SESSION['role'] !== 'staff') {
    header("Location: ../../index.php?error=notLoggedIn");
    exit;
}

header("Location: dashboard.php");
exit;

?>