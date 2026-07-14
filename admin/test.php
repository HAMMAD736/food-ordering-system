<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db-connect.php';

echo "<h1>Database Connection Successful!</h1>";

if (isset($_POST['login_test'])) {
    $u = $_POST['u'];
    $p = $_POST['p'];
    
    $q = mysqli_query($conn, "SELECT * FROM admins WHERE username='$u' AND password='$p'");
    if (mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['full_name'];
        echo "<h2 style='color:green;'>SUCCESS! Login session created. Now open: crispycorner.rf.gd/admin/orders.php</h2>";
    } else {
        echo "<h2 style='color:red;'>Invalid username or password from database!</h2>";
    }
}
?>

<form method="POST" style="margin-top:20px; padding:20px; border:1px solid #ccc; max-width:300px;">
    Username: <input type="text" name="u" required><br><br>
    Password: <input type="password" name="p" required><br><br>
    <button type="submit" name="login_test">Force Login</button>
</form>