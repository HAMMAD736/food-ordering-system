<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Temporary error check karne ke liye auth-check line ko comment kiya hai
// require_once __DIR__ . '/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace - Junk Food Nexus</title>
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2>Nexus Admin</h2>
            </div>
            <nav class="sidebar-menu">
                <a href="dashboard.php">Dashboard</a>
                <a href="orders.php">Manage Orders</a> <!-- Naya link jo orders page par le jaye ga -->
                <a href="manage-foods.php">Manage Food Catalog</a>
                <a href="manage-categories.php">Manage Categories</a>
                <a href="view-messages.php">Inbound Messages</a>
                <a href="logout.php" class="logout-btn">Log Out</a>
            </nav>
        </aside>
        <main class="workspace">
            <header class="workspace-header">
                <div class="user-meta">Welcome, <strong><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?></strong></div>
            </header>
            <div class="workspace-content">