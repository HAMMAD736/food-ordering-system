<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db-connect.php';

$error = "";

// Agar form submit hua ho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        
        // Safety: Pehle check kar rahe hain agar table khali hai toh direct new admin insert kardein
        $check_empty = mysqli_query($conn, "SELECT * FROM admins");
        if (mysqli_num_rows($check_empty) == 0) {
            mysqli_query($conn, "INSERT INTO admins (username, password, full_name) VALUES ('admin', 'admin123', 'Main Admin')");
        }

        // Admin Credentials Matching
        $stmt = mysqli_prepare($conn, "SELECT id, username, full_name FROM admins WHERE username = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_name'] = $row['full_name'];

            // Direct Redirect
            echo "<script>window.location.href='dashboard.php';</script>";
            exit();
        } else {
            $error = "Invalid Credentials! Type 'admin' and 'admin123'";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Authorization</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen font-sans">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6 tracking-tight">Gate Authorization</h2>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm font-semibold mb-4 text-center border border-red-100">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Admin Username Identifier</label>
                <input type="text" name="username" value="admin" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-800 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">System Secret Token</label>
                <input type="password" name="password" value="admin123" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-800 text-sm">
            </div>

            <button type="submit" class="w-full bg-indigo-900 hover:bg-indigo-950 text-white font-bold py-3 rounded-xl transition-all shadow-lg hover:shadow-indigo-900/20 text-sm mt-2">
                Authenticate System Access
            </button>
        </form>
    </div>

</body>
</html>