<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Nexus - Premium Food Delivery</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        premiumRed: '#DF2020',
                        premiumOrange: '#FF5722',
                        darkBg: '#121212',
                        darkCard: '#1E1E1E'
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-darkBg dark:text-slate-100 transition-colors duration-300">

<!-- Glassmorphism Header / Navbar -->
<header class="sticky top-0 z-50 bg-white/80 dark:bg-darkBg/80 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="index.php" class="flex items-center space-x-2 group">
            <span class="text-2xl font-black bg-gradient-to-r from-premiumRed to-premiumOrange bg-clip-text text-transparent transform group-hover:scale-105 transition duration-300">
                FOOD<span class="text-slate-900 dark:text-white">NEXUS</span>
            </span>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center space-x-8 font-medium">
            <a href="index.php" class="hover:text-premiumRed transition duration-300">Home</a>
            <a href="menu.php" class="hover:text-premiumRed transition duration-300">Menu</a>
            <a href="gallery.php" class="hover:text-premiumRed transition duration-300">Gallery</a>
            <a href="about.php" class="hover:text-premiumRed transition duration-300">About</a>
            <a href="contact.php" class="hover:text-premiumRed transition duration-300">Contact</a>
        </nav>

        <!-- Utility Buttons -->
        <div class="flex items-center space-x-4">
            <!-- Dark Mode Toggle Button -->
            <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:scale-110 active:scale-95 transition-all duration-200">
                <i id="theme-toggle-icon" class="fas fa-moon text-lg"></i>
            </button>

            <!-- Cart Icon with Badge -->
            <a href="cart.php" class="relative p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-premiumRed transition duration-300">
                <i class="fas fa-shopping-bag text-lg"></i>
                <span class="absolute -top-1 -right-1 bg-premiumRed text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full animate-pulse">0</span>
            </a>

            <!-- User/Admin Login Link -->
            <a href="login.php" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-premiumRed to-premiumOrange rounded-xl shadow-md hover:opacity-90 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                Login
            </a>
        </div>
    </div>
</header>

<script>
    // Dark/Light Mode Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleIcon = document.getElementById('theme-toggle-icon');
    
    // Check local storage or system preference
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
    } else {
        document.documentElement.classList.remove('dark');
        themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
    }

    themeToggleBtn.addEventListener('click', function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
        }
    });
</script>