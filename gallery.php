<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db-connect.php'; 
include 'includes/header.php'; 

// Category Filter Handle
$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
?>

<main class="min-h-screen py-16 bg-slate-50 dark:bg-[#121212] transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Title -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <h1 class="text-4xl font-black bg-gradient-to-r from-premiumRed to-premiumOrange bg-clip-text text-transparent inline-block">
                Food & Beverage Gallery
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">
                A visual showcase of our delicious signature dishes, drinks, and desserts!
            </p>
        </div>

        <!-- Dynamic Category Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <a href="gallery.php" class="px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 <?php echo empty($selected_category) ? 'bg-gradient-to-r from-premiumRed to-premiumOrange text-white shadow-lg' : 'bg-white dark:bg-darkCard text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-800 hover:border-premiumOrange' ?>">
                <i class="fas fa-border-all mr-2"></i> All Gallery
            </a>

            <?php
            $cat_query = "SELECT * FROM categories";
            $cat_result = mysqli_query($conn, $cat_query);

            if($cat_result && mysqli_num_rows($cat_result) > 0) {
                while($cat = mysqli_fetch_assoc($cat_result)) {
                    $is_active = ($selected_category == $cat['id']);
                    ?>
                    <a href="gallery.php?category=<?php echo $cat['id']; ?>" class="px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 <?php echo $is_active ? 'bg-gradient-to-r from-premiumRed to-premiumOrange text-white shadow-lg' : 'bg-white dark:bg-darkCard text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-800 hover:border-premiumOrange' ?>">
                        <?php echo htmlspecialchars($cat['title']); ?>
                    </a>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Image Grid Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php
            if (!empty($selected_category)) {
                $gal_query = "SELECT * FROM foods WHERE category_id = '$selected_category' ORDER BY id DESC";
            } else {
                $gal_query = "SELECT * FROM foods ORDER BY id DESC";
            }

            $gal_result = mysqli_query($conn, $gal_query);

            if($gal_result && mysqli_num_rows($gal_result) > 0) {
                while($row = mysqli_fetch_assoc($gal_result)) {
                    $image_name = !empty($row['image']) ? $row['image'] : 'default-food.png';
                    $image_src = "assets/images/" . $image_name;
                    ?>
                    <div class="group relative overflow-hidden bg-white dark:bg-darkCard rounded-3xl aspect-square shadow-md border border-slate-100 dark:border-slate-800/60 transition-all duration-300">
                        <img src="<?php echo $image_src; ?>" onerror="this.src='assets/images/default-food.png'" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 duration-500 transition-all">
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                            <div>
                                <h3 class="text-white font-bold text-lg"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p class="text-premiumOrange text-sm font-semibold">Rs. <?php echo number_format($row['price']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-span-full text-center py-16 bg-white dark:bg-darkCard rounded-3xl border border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-400 font-medium">Is category mein filhal koi images nahi hain.</p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>