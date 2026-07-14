<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agar admin session majood nahi hai, toh wapas login page par redirect karein
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>