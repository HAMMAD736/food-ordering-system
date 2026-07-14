<?php 
// 1. Connection check with fallback paths to prevent fatal crashes
if (file_exists('db-connect.php')) {
    include 'db-connect.php';
} elseif (file_exists('includes/db-connect.php')) {
    include 'includes/db-connect.php';
} else {
    die("<div style='color:red; padding:20px; font-family:sans-serif;'><strong>Critical Error:</strong> db-connect.php file nahi mili! Kindly check karein ke aapki database connection file kis folder mein hai.</div>");
}

include 'includes/header.php'; 

$message_sent = false;
$error_message = "";

// Form submission handle karein
if (isset($_POST['send_message'])) {
    if (!$conn) {
        $error_message = "Database connection fail ho chuka hai.";
    } else {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $msg = mysqli_real_escape_string($conn, $_POST['message']);
        
        // Target explicit table setup
        $insert_query = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$msg')";
        
        if (mysqli_query($conn, $insert_query)) {
            $message_sent = true;
        } else {
            // Agar column names ka bhi koi masla hoga to black page nahi aayega, error show hoga
            $error_message = "SQL Error: " . mysqli_error($conn);
        }
    }
}
?>

<!-- Tailwind Dynamic Integration -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    premiumOrange: '#f97316',
                    premiumRed: '#dc2626',
                    darkCard: '#1e293b'
                }
            }
        }
    }
</script>

<main class="min-h-[80vh] py-16 bg-slate-50 dark:bg-[#121212] flex items-center transition-colors duration-300 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 bg-white dark:bg-darkCard rounded-3xl overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800/60">
            
            <!-- Contact Sidebar Details -->
            <div class="md:col-span-2 bg-gradient-to-br from-premiumRed to-premiumOrange p-8 text-white flex flex-col justify-between">
                <div class="space-y-6">
                    <h2 class="text-2xl font-black">Contact Us</h2>
                    <p class="text-sm opacity-90 leading-relaxed">Have questions about your order, special catering, or suggestions for Food Nexus? Shoot us a message!</p>
                </div>
                
                <div class="space-y-4 text-sm mt-8">
                    <div class="flex items-center space-x-3">
                        <span>📞</span>
                        <span>+92 300 1234567</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span>✉️</span>
                        <span>support@foodnexus.com</span>
                    </div>
                </div>
            </div>

            <!-- Form Workspace -->
            <div class="md:col-span-3 p-8">
                <?php if ($message_sent): ?>
                    <div class="h-full flex flex-col items-center justify-center text-center space-y-3 py-12">
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 rounded-full flex items-center justify-center text-2xl font-bold animate-bounce">
                            ✓
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Message Sent!</h3>
                        <p class="text-sm text-slate-400">Thank you for reaching out. We will respond back shortly.</p>
                        <a href="contact.php" class="text-sm font-semibold text-premiumOrange hover:underline pt-2">Send another message</a>
                    </div>
                <?php else: ?>
                    <form action="contact.php" method="POST" class="space-y-5">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Get in touch</h3>
                        
                        <!-- Visible Error Alerts inside the card layout instead of blank screens -->
                        <?php if(!empty($error_message)): ?>
                            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 font-medium">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Your Name</label>
                            <input type="text" name="name" required placeholder="John Doe" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-slate-800 focus:outline-none focus:border-premiumOrange text-sm dark:text-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Email Address</label>
                            <input type="email" name="email" required placeholder="john@example.com" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-slate-800 focus:outline-none focus:border-premiumOrange text-sm dark:text-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Message</label>
                            <textarea name="message" rows="4" required placeholder="Write your message here..." class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-slate-800 focus:outline-none focus:border-premiumOrange text-sm dark:text-white transition resize-none"></textarea>
                        </div>

                        <button type="submit" name="send_message" class="w-full bg-gradient-to-r from-premiumRed to-premiumOrange text-white font-semibold py-3.5 rounded-xl shadow-lg hover:opacity-95 transition transform active:scale-[0.98]">
                            Send Message
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>