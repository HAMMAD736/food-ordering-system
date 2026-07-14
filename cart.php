<?php
// 1. Session start sabse pehle hona chahiye
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Errors show karne ke liye
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db-connect.php';

// --- CART LOGIC (Header/HTML se pehle chalna zaroori hai) ---

// 1. Add Item to Cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $food_id = intval($_GET['id']);
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (isset($_SESSION['cart'][$food_id])) {
        $_SESSION['cart'][$food_id]++;
    } else {
        $_SESSION['cart'][$food_id] = 1;
    }
    
    // Sahi redirect bina kisi warning ke
    header('Location: cart.php');
    exit();
}

// 2. Remove Item from Cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $food_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$food_id])) {
        unset($_SESSION['cart'][$food_id]);
    }
    header('Location: cart.php');
    exit();
}

// 3. Update Quantity (Plus / Minus)
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && isset($_GET['qty'])) {
    $food_id = intval($_GET['id']);
    $qty = intval($_GET['qty']);
    
    if ($qty <= 0) {
        unset($_SESSION['cart'][$food_id]);
    } else {
        $_SESSION['cart'][$food_id] = $qty;
    }
    header('Location: cart.php');
    exit();
}

// Ab jab saari logic khatam ho gayi, toh tasalli se header include karein
include 'includes/header.php';
?>

<main class="min-h-screen py-12 bg-slate-50 dark:bg-[#121212] transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">Your Food Basket</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Review your selected items before checkout.</p>
        </div>

        <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
            <div class="text-center py-16 bg-white dark:bg-darkCard rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="w-20 h-20 bg-orange-50 dark:bg-slate-800 text-premiumOrange rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-basket text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">Aapka cart khali hai!</h3>
                <p class="text-sm text-slate-400 mt-2 mb-6">Lagta hai aapne abhi tak kuch tasty select nahi kiya.</p>
                <a href="menu.php" class="px-6 py-3 rounded-xl bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-bold text-sm shadow-md hover:scale-105 transition-all inline-block">
                    Browse Menu
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-4">
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $id => $quantity) {
                        $query = "SELECT * FROM foods WHERE id = $id";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $food = mysqli_fetch_assoc($result);
                            $item_total = $food['price'] * $quantity;
                            $grand_total += $item_total;
                            
                            // Image path updated to assets/images/
                            $image_name = !empty($food['image']) ? $food['image'] : 'default-food.png';
                            $image_src = "assets/images/" . $image_name;
                            ?>
                            <div class="bg-white dark:bg-darkCard rounded-2xl p-4 flex items-center justify-between border border-slate-100 dark:border-slate-800/60 shadow-sm">
                                <div class="flex items-center space-x-4">
                                    <img src="<?php echo $image_src; ?>" onerror="this.src='assets/images/default-food.png'" class="w-16 h-16 object-cover rounded-xl bg-slate-100" alt="<?php echo htmlspecialchars($food['title']); ?>">
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-white text-base"><?php echo htmlspecialchars($food['title']); ?></h4>
                                        <p class="text-xs text-slate-400">Rs. <?php echo number_format($food['price']); ?> per item</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-6">
                                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl px-2 py-1">
                                        <a href="cart.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $quantity - 1; ?>" class="text-slate-500 dark:text-slate-400 hover:text-premiumRed px-2 font-bold">-</a>
                                        <span class="px-2 text-sm font-bold text-slate-800 dark:text-white"><?php echo $quantity; ?></span>
                                        <a href="cart.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $quantity + 1; ?>" class="text-slate-500 dark:text-slate-400 hover:text-premiumOrange px-2 font-bold">+</a>
                                    </div>
                                    
                                    <div class="text-right min-w-[80px]">
                                        <p class="font-black text-slate-800 dark:text-white text-sm">Rs. <?php echo number_format($item_total); ?></p>
                                        <a href="cart.php?action=remove&id=<?php echo $id; ?>" class="text-xs text-red-500 hover:underline mt-1 inline-block">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

                <div class="bg-white dark:bg-darkCard rounded-3xl p-6 border border-slate-100 dark:border-slate-800/60 shadow-md h-fit space-y-6">
                    <h3 class="font-black text-xl text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800/60 pb-3">Bill Summary</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">Rs. <?php echo number_format($grand_total); ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Delivery Charges</span>
                            <span class="text-green-600 font-bold">FREE</span>
                        </div>
                        <hr class="border-slate-100 dark:border-slate-800/60">
                        <div class="flex justify-between text-base font-black text-slate-800 dark:text-white">
                            <span>Grand Total</span>
                            <span class="text-premiumOrange text-lg">Rs. <?php echo number_format($grand_total); ?></span>
                        </div>
                    </div>

                    <a href="checkout.php" class="w-full text-center py-3.5 rounded-xl bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-bold text-sm shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all block mt-4">
                        Proceed to Checkout <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

            </div>
        <?php endif; ?>

    </div>
</main>

<?php include 'includes/footer.php'; ?>