<?php
require_once 'config/database.php';
require_once 'config/auth.php';

if (isset($_SESSION['username'])) {
    registrarLog($conn, $_SESSION['username'], "Logout realizado.");
}

session_destroy();
header("Location: login.php");
exit();