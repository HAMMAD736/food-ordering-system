<?php 
// Debugging active karne ke liye error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db-connect.php'; 
require_once 'includes/header.php'; 
?>

<section class="container section" style="padding: 40px 20px; font-family: Arial, sans-serif;">
    <h1 class="section-title" style="text-align: center; margin-bottom: 30px; color: #333;">Full Production Menu Catalog</h1>
    
    <div class="grid grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
        <?php
        try {
            // FIXED 1: 'c.name' ko badal kar 'c.title' kar diya hai aapke database schema ke mutabiq
            $sql = "SELECT f.*, c.title as category_name 
                    FROM foods f 
                    LEFT JOIN categories c ON f.category_id = c.id 
                    WHERE f.active = 'Yes' 
                    ORDER BY f.id DESC";
                    
            $stmt = $pdo->query($sql);
            
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) {
                    
                    // FIXED 2: '$row['image_name']' ko badal kar '$row['image']' kar diya hai
                    $imagePath = 'assets/images/placeholder.jpg'; // Default fallback image
                    
                    if (!empty($row['image'])) {
                        if (file_exists('uploads/foods/' . $row['image'])) {
                            $imagePath = 'uploads/foods/' . $row['image'];
                        } elseif (file_exists('admin/uploads/foods/' . $row['image'])) {
                            $imagePath = 'admin/uploads/foods/' . $row['image'];
                        }
                    }
                    
                    // Display category name fallback mechanism
                    $category_display = !empty($row['category_name']) ? $row['category_name'] : 'General';
                    ?>
                    
                    <div class='card' style='border: 1px solid #eee; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); background: #fff;'>
                        <div class='card-img-container' style='width: 100%; height: 200px; overflow: hidden; background: #f9f9f9; display: flex; align-items: center; justify-content: center;'>
                            <img src='<?php echo $imagePath; ?>' alt='<?php echo htmlspecialchars($row['title']); ?>' style='width: 100%; height: 100%; object-fit: cover;'>
                        </div>
                        <div class='card-body' style='padding: 20px;'>
                            <span class='badge' style='background: #ffbc00; color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: bold; display: inline-block; margin-bottom: 10px;'>
                                <?php echo htmlspecialchars($category_display); ?>
                            </span>
                            
                            <h3 style='margin: 0 0 10px 0; font-size: 1.3rem; color: #222;'><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class='price' style='font-size: 1.4rem; font-weight: bold; color: #28a745; margin: 5px 0;'>$<?php echo number_format($row['price'], 2); ?></p>
                            <p style='color: #666; font-size: 0.9rem; line-height: 1.5; margin-bottom: 15px;'><?php echo htmlspecialchars($row['description']); ?></p>
                            
                            <a href='order.php?food_id=<?php echo $row['id']; ?>' class='btn btn-primary' style='display: block; text-align: center; background: #ffbc00; color: #fff; padding: 10px; text-decoration: none; border-radius: 6px; font-weight: bold;'>Order Now</a>
                        </div>
                    </div>
                    
                    <?php
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 40px; color: #777;'>
                        <h3>No food entries found in the food array layer.</h3>
                        <p>Go to Admin Panel and make sure you have active food items listed.</p>
                      </div>";
            }
        } catch (PDOException $e) {
            // Query fail hone par detailed crash data display hoga window par
            echo "<div style='grid-column: 1/-1;' class='alert alert-danger'><strong>Data Pipeline Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
        }
        ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>