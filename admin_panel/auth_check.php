<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location:../index.php"); // Adjust path as needed
    exit();
}
?>