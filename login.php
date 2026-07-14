<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agar admin pehle se logged in hai toh direct dashboard par bhej dein
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/manage-foods.php");
    exit;
}

require_once 'includes/db-connect.php';

$error_msg = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Query to check admin (assuming table name is 'admin' or 'admins')
        // Aap apne database table ke mutabiq name adjust kar sakti hain
        $query = "SELECT * FROM admin WHERE username='$username' AND password='$password' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $admin_data = mysqli_fetch_assoc($result);
            
            $_SESSION['admin_id'] = $admin_data['id'];
            $_SESSION['admin_username'] = $admin_data['username'];
            $_SESSION['admin_name'] = $admin_data['name'] ?? 'Admin';

            header("Location: admin/manage-foods.php");
            exit;
        } else {
            $error_msg = "Invalid Username or Password!";
        }
    } else {
        $error_msg = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Nexus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 dark:bg-[#121212] min-h-screen flex items-center justify-center font-sans px-4 transition-colors duration-300">

    <div class="max-w-md w-full bg-white dark:bg-[#1e1e1e] p-8 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800/60 relative overflow-hidden">
        
        <!-- Top Decorative Gradient -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#ff3838] to-[#ff8c00]"></div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-black bg-gradient-to-r from-[#ff3838] to-[#ff8c00] bg-clip-text text-transparent inline-block tracking-tight">
                FOOD NEXUS
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium">Welcome back! Please login to your account.</p>
        </div>

        <!-- Error Message Alert -->
        <?php if (!empty($error_msg)): ?>
            <div class="mb-5 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span><?php echo $error_msg; ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" required placeholder="Enter username" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-[#181818] border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:outline-none focus:border-[#ff8c00] dark:focus:border-[#ff8c00] dark:text-white transition duration-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="Enter password" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-[#181818] border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:outline-none focus:border-[#ff8c00] dark:focus:border-[#ff8c00] dark:text-white transition duration-200">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" name="login" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#ff3838] to-[#ff8c00] text-white font-bold text-sm shadow-md hover:opacity-95 active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    Sign In
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a href="menu.php" class="text-xs text-slate-400 hover:text-[#ff8c00] transition duration-200">
                <i class="fas fa-arrow-left mr-1"></i> Back to Menu
            </a>
        </div>
    </div>

</body>
</html>