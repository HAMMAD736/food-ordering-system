<?php 
require_once '../includes/db-connect.php'; 
require_once '../includes/admin-header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
$stmt->execute([$id]);
$food = $stmt->fetch();

if (!$food) {
    header('Location: manage-foods.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $price       = (float)$_POST['price'];
    $featured    = $_POST['featured'];
    $active      = $_POST['active'];
    $current_img = $_POST['current_image'];
    $new_image   = $current_img;

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image = "Food-Asset-".rand(0000,9999).".". $ext;
        $sourcePath = $_FILES['image']['tmp_name'];
        if (move_uploaded_file($sourcePath, "../uploads/foods/" . $new_image)) {
            if(!empty($current_img) && file_exists("../uploads/foods/" . $current_img)) {
                unlink("../uploads/foods/" . $current_img);
            }
        }
    }

    $update = $pdo->prepare("UPDATE foods SET category_id = ?, title = ?, description = ?, price = ?, image_name = ?, featured = ?, active = ? WHERE id = ?");
    $update->execute([$category_id, $title, $description, $price, $new_image, $featured, $active, $id]);
    header('Location: manage-foods.php');
    exit;
}
?>
<h1>Modify Existing Data Context Matrix</h1>
<form action="edit-food.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($food['image_name'] ?? ''); ?>">
    <div class="form-group">
        <label>Food Item Title Name</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($food['title']); ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label>Category Reference</label>
        <select name="category_id" required class="form-control">
            <?php
            $cats = $pdo->query("SELECT * FROM categories");
            while($c = $cats->fetch()) {
                $sel = ($c['id'] == $food['category_id']) ? "selected" : "";
                echo "<option value='{$c['id']}' {$sel}>".htmlspecialchars($c['name'])."</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Description Payload Data</label>
        <textarea name="description" rows="4" class="form-control"><?php echo htmlspecialchars($food['description']); ?></textarea>
    </div>
    <div class="form-group">
        <label>Price Value Metric</label>
        <input type="number" step="0.01" name="price" value="<?php echo $food['price']; ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label>Current Visual Presentation Asset</label><br>
        <?php if($food['image_name']): ?>
            <img src="../uploads/foods/<?php echo $food['image_name']; ?>" width="120" style="margin-bottom:10px;"><br>
        <?php endif; ?>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label>Featured Assignment Profile</label><br>
        <input type="radio" name="featured" value="Yes" <?php if($food['featured']=='Yes') echo 'checked'; ?>> Yes
        <input type="radio" name="featured" value="No" <?php if($food['featured']=='No') echo 'checked'; ?>> No
    </div>
    <div class="form-group">
        <label>System Processing Visibility (Active Status)</label><br>
        <input type="radio" name="active" value="Yes" <?php if($food['active']=='Yes') echo 'checked'; ?>> Yes
        <input type="radio" name="active" value="No" <?php if($food['active']=='No') echo 'checked'; ?>> No
    </div>
    <button type="submit" class="btn btn-primary">Commit Dynamic Modifications</button>
</form>
<?php require_once '../includes/admin-footer.php'; ?>