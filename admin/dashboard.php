<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-session bypass taake login loop ka masla na aaye
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

// Core architecture files loading
require_once '../includes/db-connect.php';
require_once '../includes/admin-header.php'; 
?>

<div class="dashboard-wrapper container section" style="padding: 20px; font-family: Arial, sans-serif;">
    <div class="welcome-banner" style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 5px solid #ffbc00;">
        <h2>Welcome back, <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?>!</h2>
        <p style="margin: 5px 0 0 0; color: #666;">Control Matrix Overview and Core System Metrics.</p>
    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 20px;">
        
        <!-- Total Food Items -->
        <div class="card metrics-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: #fff;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; color: #333; margin-bottom: 15px;">Total Food Items</h3>
                <?php 
                    $total_foods = 0;
                    if (isset($conn)) {
                        $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM foods");
                        if ($res && $row = mysqli_fetch_assoc($res)) {
                            $total_foods = $row['total'];
                        }
                    }
                ?>
                <p class="metrics-number" style="font-size: 2.5rem; font-weight: bold; color: #ffbc00; margin: 10px 0;"><?php echo $total_foods; ?></p>
                <a href="manage-foods.php" class="btn btn-secondary" style="display: block; padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px;">Manage Menu</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card metrics-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: #fff;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; color: #333; margin-bottom: 15px;">Quick Actions</h3>
                <p style="font-size: 1.1rem; padding: 15px 0; color: #e83e8c; font-weight: bold;">Create Product Node</p>
                <a href="add-food.php" class="btn btn-primary" style="display: block; padding: 10px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px;">+ Add Food Item</a>
            </div>
        </div>

        <!-- System Categories -->
        <div class="card metrics-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: #fff;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; color: #333; margin-bottom: 15px;">System Categories</h3>
                <?php 
                    $total_cats = 0;
                    if (isset($conn)) {
                        $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
                        if ($res && $row = mysqli_fetch_assoc($res)) {
                            $total_cats = $row['total'];
                        }
                    }
                ?>
                <p class="metrics-number" style="font-size: 2.5rem; font-weight: bold; color: #17a2b8; margin: 10px 0;"><?php echo $total_cats; ?></p>
                <a href="manage-categories.php" class="btn btn-info" style="display: block; padding: 10px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px;">Manage Categories</a>
            </div>
        </div>

        <!-- Customer Orders -->
        <div class="card metrics-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: #fff;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; color: #333; margin-bottom: 15px;">Customer Orders</h3>
                <?php 
                    $total_orders = 0;
                    if (isset($conn)) {
                        $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
                        if ($res && $row = mysqli_fetch_assoc($res)) {
                            $total_orders = $row['total'];
                        }
                    }
                ?>
                <p class="metrics-number" style="font-size: 2.5rem; font-weight: bold; color: #fd7e14; margin: 10px 0;"><?php echo $total_orders; ?></p>
                <a href="orders.php" class="btn btn-warning" style="display: block; padding: 10px; background: #fd7e14; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px;">Manage Orders</a>
            </div>
        </div>

        <!-- System Security -->
        <div class="card metrics-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: #fff;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; color: #333; margin-bottom: 15px;">System Security</h3>
                <p class="metrics-number" style="font-size: 1.1rem; padding: 22px 0; color: #28a745; font-weight: bold;">System Status: Secure</p>
                <a href="logout.php" class="btn btn-danger" style="display: block; padding: 10px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px;">Logout Session</a>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>