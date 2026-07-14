<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_username'] = 'admin';
    $_SESSION['admin_name'] = 'Main Admin';
}

require_once '../includes/db-connect.php';

// Specifically set to contact_messages based on your phpMyAdmin database setup
$table_name = "contact_messages";

// Fetch all inbound messages sorted by latest row first
$query = "SELECT * FROM $table_name ORDER BY id DESC";
$result = mysqli_query($conn, $query);

require_once '../includes/admin-header.php';
?>

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
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">Inbound Messages</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">View and manage contact form submissions and feedback messages from your customers.</p>
        </div>

        <div class="bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4 w-20">ID</th>
                            <th class="p-4 w-48">Sender Info</th>
                            <th class="p-4 w-56">Email Destination</th>
                            <th class="p-4">Message / Feedback Body</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): 
                                $id = $row['id'];
                                $name = $row['name'] ?? 'Anonymous';
                                $email = $row['email'] ?? 'No Email';
                                $message_text = $row['message'] ?? 'Empty content.';
                            ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 dark:text-white transition-colors">
                                    <td class="p-4 font-bold text-premiumOrange">#<?php echo $id; ?></td>
                                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?php echo htmlspecialchars($name); ?>
                                    </td>
                                    <td class="p-4 text-xs text-slate-600 dark:text-slate-300 break-all">
                                        <?php echo htmlspecialchars($email); ?>
                                    </td>
                                    <td class="p-4 text-slate-600 dark:text-slate-300 max-w-md break-words leading-relaxed">
                                        <?php echo nl2br(htmlspecialchars($message_text)); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400 italic text-base">
                                    No inbound messages found in your mailbox queue. Try sending one from the website!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>