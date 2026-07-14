<?php
// InfinityFree Database Configuration
$servername = "sql104.infinityfree.com"; // Aapka server name
$username = "if0_42061343";            // Aapka vpanel username
$password = "aKvc9p9DYkXHe"; // !!! APNA REAL PASSWORD YAHAN LIKHEIN !!!
$dbname = "if0_42061343_Crispycorner";  // Aapka exact database name

// Sahi connection logic (Order: host, user, password, database)
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>