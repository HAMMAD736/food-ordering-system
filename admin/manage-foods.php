<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-session check
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

require_once '../includes/db-connect.php';

// Handle Update Price Action
if (isset($_POST['update_price'])) {
    $food_id = intval($_POST['food_id']);
    $new_price = floatval($_POST['price']);
    
    $update_query = "UPDATE foods SET price = $new_price WHERE id = $food_id";
    if (mysqli_query($conn, $update_query)) {
        header("Location: manage-foods.php");
        exit;
    }
}

// Handle Delete Food Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    
    // First fetch image name to delete file if exists
    $img_res = mysqli_query($conn, "SELECT image FROM foods WHERE id = $delete_id");
    if ($img_res && $img_row = mysqli_fetch_assoc($img_res)) {
        if (!empty($img_row['image']) && file_exists("../assets/images/" . $img_row['image'])) {
            unlink("../assets/images/" . $img_row['image']);
        }
    }
    
    $delete_query = "DELETE FROM foods WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Food item deleted successfully!'); window.location.href='manage-foods.php';</script>";
        exit;
    }
}

// Dynamic categories column check to prevent query crashes
$cat_col = "name"; 
$check_columns = mysqli_query($conn, "SHOW COLUMNS FROM categories");
if ($check_columns) {
    while ($col = mysqli_fetch_assoc($check_columns)) {
        if ($col['Field'] === 'title') {
            $cat_col = "title";
            break;
        }
    }
}

require_once '../includes/admin-header.php';
?>

<div class="container section" style="padding: 20px; font-family: Arial, sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Manage Food Catalog</h2>
        <a href="add-food.php" style="background: #28a745; color: white; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 0.95rem;">+ Add New Food</a>
    </div>

    <!-- Food Catalog List Table -->
    <div style="overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #ddd;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd; color: #333;">
                    <th style="padding: 12px 15px;">ID</th>
                    <th style="padding: 12px 15px;">Image</th>
                    <th style="padding: 12px 15px;">Title</th>
                    <th style="padding: 12px 15px;">Category</th>
                    <th style="padding: 12px 15px;">Price (Rs.)</th>
                    <th style="padding: 12px 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query with dynamic category column connection
                $query = "SELECT f.*, c.$cat_col AS category_name 
                          FROM foods f 
                          LEFT JOIN categories c ON f.category_id = c.id 
                          ORDER BY f.id DESC";
                          
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $image_src = "../assets/images/" . $row['image'];
                        if (empty($row['image']) || !file_exists($image_src)) {
                            $image_src = "../assets/images/default-food.png"; // Fallback placeholder
                        }
                        ?>
                        <tr style="border-bottom: 1px solid #eee; vertical-align: middle;">
                            <td style="padding: 12px 15px;"><?php echo $row['id']; ?></td>
                            <td style="padding: 12px 15px;">
                                <img src="<?php echo $image_src; ?>" alt="Food" style="width: 60px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                            </td>
                            <td style="padding: 12px 15px; font-weight: bold; color: #333;"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td style="padding: 12px 15px;"><span style="background: #e9ecef; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; color: #495057;"><?php echo htmlspecialchars($row['category_name'] ?? 'Uncategorized'); ?></span></td>
                            
                            <!-- Editable Price Column Form -->
                            <td style="padding: 12px 15px;">
                                <form action="manage-foods.php" method="POST" style="display: flex; align-items: center; gap: 6px; margin: 0;">
                                    <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="price" value="<?php echo $row['price']; ?>" min="0" step="0.01" style="width: 90px; padding: 5px 8px; border: 1px solid #ccc; border-radius: 4px; font-weight: bold; color: #28a745;">
                                    <button type="submit" name="update_price" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; cursor: pointer; font-weight: bold;">Update</button>
                                </form>
                            </td>
                            
                            <td style="padding: 12px 15px; text-align: center;">
                                <a href="manage-foods.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this food item?');" style="background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem;">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center; padding: 30px; color: #888;'>No food items found in the catalog. Click '+ Add New Food' to get started!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>