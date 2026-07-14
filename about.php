<?php 
include 'db-connect.php'; 
include 'includes/header.php'; 
?>

<main class="min-h-screen py-16 bg-slate-50 dark:bg-[#121212] transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-16">
            <h1 class="text-4xl font-black bg-gradient-to-r from-premiumRed to-premiumOrange bg-clip-text text-transparent inline-block">
                About Food Nexus
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">
                Discover our journey of delivering happiness and elite flavors to your doorstep.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div class="relative flex justify-center">
                <div class="absolute w-72 h-72 bg-premiumRed/10 rounded-full blur-3xl -z-10"></div>
                <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&q=80&w=600" alt="Our Kitchen" class="rounded-3xl shadow-2xl w-full max-w-[500px] aspect-[4/3] object-cover border-4 border-white dark:border-neutral-800">
            </div>
            
            <div class="space-y-6 text-center lg:text-left">
                <h2 class="text-3xl font-bold tracking-tight">We Cook With Passion & Deliver With Speed</h2>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                    Food Nexus started with a simple vision: to bridge the gap between gourmet culinary skills and ultimate convenience. We partner with the finest culinary minds to craft meals that don't just satisfy hunger, but elevate your mood.
                </p>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                    Every dish is prepared under strict hygiene standards using handpicked fresh ingredients, ensuring that what reaches your table is nothing short of perfection.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-8 bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-md hover:shadow-xl transition duration-300 space-y-4">
                <div class="w-12 h-12 bg-premiumRed/10 text-premiumRed rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="text-lg font-bold">Premium Taste</h3>
                <p class="text-sm text-slate-400 leading-relaxed">Crafted exclusively by elite chefs who understand the fine art of spices and balancing flavors.</p>
            </div>

            <div class="p-8 bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-md hover:shadow-xl transition duration-300 space-y-4">
                <div class="w-12 h-12 bg-premiumOrange/10 text-premiumOrange rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="text-lg font-bold">Lightning Fast</h3>
                <p class="text-sm text-slate-400 leading-relaxed">Our advanced logistics model ensures your food is delivered fresh and steaming hot within minutes.</p>
            </div>

            <div class="p-8 bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-md hover:shadow-xl transition duration-300 space-y-4">
                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3 class="text-lg font-bold">Hygiene First</h3>
                <p class="text-sm text-slate-400 leading-relaxed">From packaging to delivery routes, flawless sanitization protocols are maintained at every single layer.</p>
            </div>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>