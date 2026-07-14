<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

require_once '../includes/db-connect.php';

$message = "";

// Dynamic column check
$column_to_use = "name";
$check_columns = mysqli_query($conn, "SHOW COLUMNS FROM categories");
if ($check_columns) {
    while ($col = mysqli_fetch_assoc($check_columns)) {
        if ($col['Field'] === 'title') {
            $column_to_use = "title";
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    
    $image_name = "";
    
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $target_dir = "../assets/images/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    if (!empty($title) && $price > 0 && $category_id > 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO foods (title, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdis", $title, $description, $price, $category_id, $image_name);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Food item added successfully!'); window.location.href='manage-foods.php';</script>";
            exit;
        } else {
            $message = "<div style='color: red; margin-bottom: 15px;'>Database error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Please fill all required fields correctly.</div>";
    }
}

require_once '../includes/admin-header.php';
?>

<div class="container section" style="padding: 20px; font-family: Arial, sans-serif; max-width: 600px;">
    <h2 style="margin-bottom: 20px;">Add New Food Item</h2>

    <?php echo $message; ?>

    <form action="add-food.php" method="POST" enctype="multipart/form-data" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #ddd;">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Food Name *</label>
            <input type="text" name="title" placeholder="e.g. Zinger Burger, Pepperoni Pizza" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Category *</label>
            <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                <option value="">Select Main Category</option>
                <?php
                $cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY $column_to_use ASC");
                if ($cat_res && mysqli_num_rows($cat_res) > 0) {
                    while ($cat = mysqli_fetch_assoc($cat_res)) {
                        $cat_name = isset($cat['title']) ? $cat['title'] : ($cat['name'] ?? 'Unnamed');
                        echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat_name) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Price (Rs.) *</label>
            <input type="number" step="0.01" name="price" placeholder="e.g. 450" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
            <textarea name="description" placeholder="Short description of the food item..." rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;"></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Food Image</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" name="submit" style="background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; flex: 1;">Save Food Item</button>
            <a href="manage-foods.php" style="background: #6c757d; color: white; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; text-align: center;">Cancel</a>
        </div>

    </form>
</div>

<?php require_once '../includes/admin-footer.php'; ?>