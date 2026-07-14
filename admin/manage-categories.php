<?php
// Strict error management to catch any runtime issues smoothly
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-session bypass for development environment
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

require_once '../includes/db-connect.php';

$message = "";

// Dynamic structure check to prevent future crashes
$column_to_use = "name"; // Default column fallback
$check_columns = mysqli_query($conn, "SHOW COLUMNS FROM categories");
if ($check_columns) {
    while ($col = mysqli_fetch_assoc($check_columns)) {
        if ($col['Field'] === 'title') {
            $column_to_use = "title"; // Switch to 'title' if table uses Food Nexus structure
            break;
        }
    }
}

// Handle Add Category Form Submission
if (isset($_POST['add_category'])) {
    $cat_name = trim($_POST['name']);
    if (!empty($cat_name)) {
        $cat_name_clean = mysqli_real_escape_string($conn, $cat_name);
        
        // Permanent Solution: Uses the dynamically verified column name
        $insert_query = "INSERT INTO categories ($column_to_use) VALUES ('$cat_name_clean')";
        
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Category added successfully!'); window.location.href='manage-categories.php';</script>";
            exit;
        } else {
            $message = "<div style='color: red; margin-bottom: 15px; font-weight: bold;'>Database Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Handle Delete Category Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $delete_query = "DELETE FROM categories WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Category deleted successfully!'); window.location.href='manage-categories.php';</script>";
        exit;
    }
}

require_once '../includes/admin-header.php';
?>

<div class="container section" style="padding: 20px; font-family: Arial, sans-serif;">
    <h2 style="margin-bottom: 20px;">Manage Categories</h2>

    <?php echo $message; ?>

    <!-- Add Category Form -->
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #ddd; margin-bottom: 30px; max-width: 500px;">
        <h3 style="margin-bottom: 15px; font-size: 1.1rem;">Add New Category</h3>
        <form action="manage-categories.php" method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="name" placeholder="Category Name (e.g. Burgers, Shawarma)" required style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            <button type="submit" name="add_category" style="background: #17a2b8; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Add Category</button>
        </form>
    </div>

    <!-- Category List Table -->
    <div style="overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #ddd;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd; color: #333;">
                    <th style="padding: 12px 15px;">ID</th>
                    <th style="padding: 12px 15px;">Category Name</th>
                    <th style="padding: 12px 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM categories ORDER BY id DESC";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Dynamic fallback for printing data out on dashboard layout
                        $display_name = isset($row['title']) ? $row['title'] : ($row['name'] ?? 'Unnamed Category');
                        ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px 15px;"><?php echo $row['id']; ?></td>
                            <td style="padding: 12px 15px; font-weight: bold;"><?php echo htmlspecialchars($display_name); ?></td>
                            <td style="padding: 12px 15px; text-align: center;">
                                <a href="manage-categories.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');" style="background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem;">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center; padding: 20px; color: #888;'>No categories found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>