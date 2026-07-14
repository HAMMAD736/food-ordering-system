<?php 
// Database connection file include karein
include 'db-connect.php'; 
include 'includes/header.php'; // Apni header file ka exact path confirm kar lein
?>

<!-- Hero Section -->
<section class="relative overflow-hidden min-h-[85vh] flex items-center bg-gradient-to-br from-white via-slate-50 to-orange-50/30 dark:from-darkBg dark:via-darkBg dark:to-neutral-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
        
        <!-- Left Side: Content -->
        <div class="space-y-8 text-center lg:text-left">
            <div class="inline-flex items-center space-x-2 bg-premiumRed/10 text-premiumRed dark:bg-premiumRed/20 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider">
                <i class="fas fa-fire animate-bounce"></i> <span>Super Fast Delivery in Lahore</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-tight">
                Craving Hot & <br class="hidden sm:inline"> Delicious Food? <br>
                <span class="bg-gradient-to-r from-premiumRed to-premiumOrange bg-clip-text text-transparent">Food Nexus</span> Is Here.
            </h1>
            <p class="text-base sm:text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto lg:mx-0">
                Order your favorite dishes from top chefs around town. Fresh ingredients, lightning-fast delivery, and premium tastes guaranteed.
            </p>
            
            <!-- Beautiful Search/Call-to-Action Bar -->
            <div class="max-w-md mx-auto lg:mx-0 p-2 bg-white dark:bg-darkCard rounded-2xl shadow-xl flex items-center border border-slate-200/60 dark:border-slate-800">
                <div class="pl-3 text-slate-400">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" placeholder="Search for burger, pizza, biryani..." class="w-full pl-3 pr-2 py-3 bg-transparent text-sm focus:outline-none dark:text-white" />
                <button class="bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-medium text-sm px-6 py-3 rounded-xl shadow-lg hover:opacity-95 transition-all whitespace-nowrap">
                    Find Food
                </button>
            </div>
        </div>

        <!-- Right Side: Animated Floating Food Image -->
        <div class="flex justify-center relative">
            <div class="absolute w-72 h-72 sm:w-96 sm:h-96 bg-premiumOrange/20 dark:bg-premiumOrange/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
            <!-- Standard Placeholder Image - Aap iski jagah apna transparent food asset laga saktay hain -->
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=600" alt="Delicious Food" class="rounded-3xl shadow-2xl w-11/12 max-w-[450px] object-cover transform hover:scale-105 duration-500 transition-all border-4 border-white dark:border-neutral-800">
        </div>
    </div>
</section>

<!-- Categories Section (Dynamic from Database) -->
<section class="py-16 bg-white dark:bg-darkBg transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-3xl font-bold tracking-tight">In the mood for something specific?</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Explore our premium selection of categories.</p>
        </div>

        <!-- Horizontal Scrollable/Grid Categories -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php
            // Categories query
            $cat_query = "SELECT * FROM categories LIMIT 6";
            $cat_result = mysqli_query($conn, $cat_query);

            if(mysqli_num_rows($cat_result) > 0) {
                while($cat = mysqli_fetch_assoc($cat_result)) {
                    ?>
                    <a href="menu.php?category=<?php echo $cat['id']; ?>" class="group flex flex-col items-center p-6 bg-slate-50 dark:bg-darkCard rounded-2xl border border-slate-100 dark:border-slate-800/60 hover:border-premiumOrange hover:shadow-xl dark:hover:border-premiumOrange transform hover:-translate-y-1 transition-all duration-300">
                        <div class="w-16 h-16 bg-premiumOrange/10 dark:bg-premiumOrange/20 rounded-full flex items-center justify-center group-hover:bg-premiumOrange transition duration-300">
                            <i class="fas fa-utensils text-premiumOrange group-hover:text-white text-xl transition duration-300"></i>
                        </div>
                        <h3 class="mt-4 font-semibold text-sm group-hover:text-premiumOrange transition duration-300"><?php echo $cat['name']; ?></h3>
                    </a>
                    <?php
                }
            } else {
                echo "<p class='col-span-full text-center text-slate-400 text-sm'>No categories found.</p>";
            }
            ?>
        </div>
    </div>
</section>

<!-- Featured Food Items (Dynamic from Database) -->
<section class="py-16 bg-slate-50 dark:bg-neutral-900/40 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-12">
            <div class="text-center sm:text-left">
                <h2 class="text-3xl font-bold tracking-tight">Trending This Week</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Most ordered premium dishes right now.</p>
            </div>
            <a href="menu.php" class="mt-4 sm:mt-0 inline-flex items-center space-x-2 text-premiumRed font-semibold group hover:text-premiumOrange transition">
                <span>View Full Menu</span> <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        <!-- Foods Premium Grid Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Foods query
            $food_query = "SELECT * FROM foods LIMIT 6";
            $food_result = mysqli_query($conn, $food_query);

            if(mysqli_num_rows($food_result) > 0) {
                while($food = mysqli_fetch_assoc($food_result)) {
                    ?>
                    <div class="group bg-white dark:bg-darkCard rounded-3xl overflow-hidden shadow-md hover:shadow-2xl border border-slate-100 dark:border-slate-800/60 transition-all duration-300 flex flex-col h-full">
                        
                        <!-- Image Container with Badges -->
                        <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
                            <!-- Dummy default image handles missing files gracefully -->
                            <img src="uploads/foods/<?php echo $food['image']; ?>" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=500'" alt="<?php echo $food['title']; ?>" class="w-full h-full object-cover transform group-hover:scale-110 duration-500 transition-all">
                            <span class="absolute top-4 left-4 bg-white/90 dark:bg-darkCard/90 backdrop-blur-md text-xs font-bold px-3 py-1.5 rounded-xl text-slate-800 dark:text-white shadow-sm">
                                <i class="fas fa-clock text-premiumOrange mr-1"></i> 25 mins
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col flex-grow justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-400 font-medium">
                                    <span>Premium Quality</span>
                                    <span class="text-amber-500"><i class="fas fa-star"></i> 4.8</span>
                                </div>
                                <h3 class="font-bold text-xl line-clamp-1 group-hover:text-premiumRed transition duration-300">
                                    <?php echo $food['title']; ?>
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2">
                                    <?php echo $food['description']; ?>
                                </p>
                            </div>

                            <!-- Footer Section inside Card -->
                            <div class="flex items-center justify-between pt-6 mt-4 border-t border-slate-100 dark:border-slate-800/60">
                                <span class="text-2xl font-black text-slate-900 dark:text-white">
                                    Rs. <?php echo number_format($food['price']); ?>
                                </span>
                                <a href="cart.php?action=add&id=<?php echo $food['id']; ?>" class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-r from-premiumRed to-premiumOrange text-white shadow-md hover:scale-105 active:scale-95 transition-all duration-200">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='col-span-full text-center text-slate-400 py-12'>No delicious food items found in the database yet.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>