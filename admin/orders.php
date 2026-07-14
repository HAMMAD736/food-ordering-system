<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-session bypass for development environment
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

// Ensure database connection is pointing to the right path from admin folder
include '../includes/db-connect.php'; 

// Dynamic structure handling to detect status and date columns
$status_col = 'status';
$date_col = 'created_at';
$addr_col = 'delivery_address';

$check_cols = mysqli_query($conn, "SHOW COLUMNS FROM orders");
if ($check_cols) {
    while ($col = mysqli_fetch_assoc($check_cols)) {
        if ($col['Field'] === 'order_status') { $status_col = 'order_status'; }
        if ($col['Field'] === 'order_date') { $date_col = 'order_date'; }
        if ($col['Field'] === 'customer_address') { $addr_col = 'customer_address'; }
    }
}

// Handle Status Update (Pending to Completed / Cancelled)
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE orders SET $status_col = '$new_status' WHERE id = $order_id";
    mysqli_query($conn, $update_query);
    header("Location: orders.php");
    exit();
}

// Handle Order Deletion
if (isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);
    
    $delete_query = "DELETE FROM orders WHERE id = $order_id";
    mysqli_query($conn, $delete_query);
    header("Location: orders.php");
    exit();
}

// Fetch all orders sorted by latest first using verified date column
$query = "SELECT * FROM orders ORDER BY $date_col DESC";
$result = mysqli_query($conn, $query);

include '../includes/admin-header.php'; // Sahi admin header path include kiya hai
?>

<!-- Permanent Style Solution: Tailwind Framework Injection for InfinityFree -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    premiumOrange: '#f97316',
                    darkCard: '#1e293b'
                }
            }
        }
    }
</script>

<div class="p-6 bg-slate-50 min-h-screen dark:bg-[#121212] transition-colors duration-300 font-sans">
    <div class="max-w-6xl mx-auto space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">Customer Orders</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Manage incoming customer food orders, change delivery statuses, or clear entries smoothly.</p>
        </div>

        <div class="bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">Order ID</th>
                            <th class="p-4">Customer Details</th>
                            <th class="p-4">Delivery Address</th>
                            <th class="p-4">Total Amount</th>
                            <th class="p-4">Date & Time</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): 
                                $status = isset($row[$status_col]) ? $row[$status_col] : 'Pending';
                                $order_date = isset($row[$date_col]) ? $row[$date_col] : 'now';
                                $address = isset($row[$addr_col]) ? $row[$addr_col] : ($row['address'] ?? 'N/A');
                                $c_name = $row['customer_name'] ?? ($row['name'] ?? 'Unknown');
                                $c_phone = $row['customer_phone'] ?? ($row['phone'] ?? 'N/A');
                                $total_amt = $row['total_amount'] ?? ($row['price'] ?? 0);
                                
                                // Dynamic CSS for Badges
                                $badge_class = "bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50"; 
                                if ($status === 'Delivered' || $status === 'Completed') {
                                    $badge_class = "bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50";
                                } elseif ($status === 'Cancelled') {
                                    $badge_class = "bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 border border-red-200/50";
                                } elseif ($status === 'Processing') {
                                    $badge_class = "bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/50";
                                }
                            ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 dark:text-white transition-colors alignment-middle">
                                    <td class="p-4 font-bold text-premiumOrange">#<?php echo $row['id']; ?></td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-200"><?php echo htmlspecialchars($c_name); ?></div>
                                        <div class="text-xs text-slate-400 font-mono mt-0.5"><?php echo htmlspecialchars($c_phone); ?></div>
                                    </td>
                                    <td class="p-4 max-w-xs break-words text-slate-600 dark:text-slate-300" title="<?php echo htmlspecialchars($address); ?>">
                                        <?php echo htmlspecialchars($address); ?>
                                    </td>
                                    <td class="p-4 font-black text-slate-800 dark:text-slate-200">
                                        Rs. <?php echo number_format($total_amt, 0); ?>
                                    </td>
                                    <td class="p-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <?php echo date('M d, Y - h:i A', strtotime($order_date)); ?>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?php echo $badge_class; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2 max-w-[260px] mx-auto">
                                            <!-- Update Status Form -->
                                            <form action="orders.php" method="POST" class="flex items-center gap-1.5">
                                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="px-2 py-1.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-1 focus:ring-premiumOrange focus:outline-none text-slate-700 dark:text-slate-300 cursor-pointer">
                                                    <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                                    <option value="Processing" <?php if($status == 'Processing') echo 'selected'; ?>>Processing</option>
                                                    <option value="Delivered" <?php if($status == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                                    <option value="Cancelled" <?php if($status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" name="update_status" class="px-2.5 py-1.5 bg-slate-800 text-white dark:bg-slate-700 text-xs font-bold rounded-lg hover:bg-premiumOrange dark:hover:bg-premiumOrange transition-all shadow-sm">
                                                    Update
                                                </button>
                                            </form>

                                            <!-- Inline Delete Form with Professional English Alert Box -->
                                            <form action="orders.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this order?');" class="inline">
                                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete_order" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-colors border border-transparent hover:border-red-200/50" title="Delete Order">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400 italic text-base">No orders received yet. Everything looks quiet!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-footer.php'; // Sahi admin header path include kiya hai ?>