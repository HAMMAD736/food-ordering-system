<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db-connect.php';

// If cart is empty, redirect back to cart page
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Calculate Total Amount
$grand_total = 0;
foreach ($_SESSION['cart'] as $id => $quantity) {
    $query = "SELECT price FROM foods WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $food = mysqli_fetch_assoc($result);
        $grand_total += ($food['price'] * $quantity);
    }
}

$order_success = false;
$error_msg = "";

// When user clicks "Confirm Order"
if (isset($_POST['place_order'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $current_date = date('Y-m-d H:i:s'); // Required for your order_date column
    
    if (!empty($name) && !empty($phone) && !empty($address)) {
        // Insert order matching your specific table columns perfectly
        $insert_query = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount, payment_method, order_status, order_date) 
                         VALUES ('$name', '$phone', '$address', '$grand_total', 'Cash on Delivery', 'Pending', '$current_date')";
                         
        if (mysqli_query($conn, $insert_query)) {
            // Order successful, clear the cart
            unset($_SESSION['cart']);
            $order_success = true;
        } else {
            $error_msg = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $error_msg = "Please fill in all the required fields.";
    }
}

include 'includes/header.php';
?>

<main class="min-h-screen py-12 bg-slate-50 dark:bg-[#121212] transition-colors duration-300">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if ($order_success): ?>
            <!-- Success Message Screen -->
            <div class="text-center py-16 bg-white dark:bg-darkCard rounded-3xl border border-emerald-100 dark:border-emerald-900/30 shadow-xl space-y-6">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-check-circle text-4xl"></i>
                </div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white">Order Placed Successfully!</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto text-sm px-4">
                    Thank you for your order! Our delivery agent will contact you shortly. Please keep your cash ready for Cash on Delivery.
                </p>
                <a href="menu.php" class="px-6 py-3 rounded-xl bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-bold text-sm shadow-md hover:scale-105 transition-all inline-block">
                    Order Something Else
                </a>
            </div>
        <?php else: ?>
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white">Checkout Details</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Please enter your shipping information below.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- Form Box -->
                <div class="md:col-span-3 bg-white dark:bg-darkCard rounded-3xl p-6 border border-slate-100 dark:border-slate-800/60 shadow-md">
                    <?php if(!empty($error_msg)): ?>
                        <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-bold mb-4"><?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form action="checkout.php" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Full Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl text-sm focus:outline-none focus:border-premiumOrange dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Phone Number</label>
                            <input type="text" name="phone" required placeholder="e.g., 03001234567" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl text-sm focus:outline-none focus:border-premiumOrange dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Delivery Address</label>
                            <textarea name="address" rows="4" required placeholder="House number, Street, Area details..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl text-sm focus:outline-none focus:border-premiumOrange dark:text-white"></textarea>
                        </div>
                        
                        <button type="submit" name="place_order" class="w-full py-3.5 mt-2 rounded-xl bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-bold text-sm shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all">
                            Confirm Order (COD)
                        </button>
                    </form>
                </div>

                <!-- Order Short Summary -->
                <div class="md:col-span-2 bg-slate-100/70 dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/40 dark:border-slate-800/40 h-fit space-y-4">
                    <h3 class="font-bold text-base text-slate-700 dark:text-slate-300">Order Summary</h3>
                    <div class="flex justify-between items-center text-sm border-t border-dashed border-slate-300 dark:border-slate-700 pt-3">
                        <span class="text-slate-500">Total Payable Amount:</span>
                        <span class="text-xl font-black text-premiumRed">Rs. <?php echo number_format($grand_total); ?></span>
                    </div>
                    <p class="text-[11px] text-slate-400 italic"><i class="fas fa-info-circle"></i> Currently, only Cash on Delivery (COD) is available.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php include 'includes/footer.php'; ?>