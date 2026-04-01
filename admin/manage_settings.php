<?php
require_once '../config.php';
check_login();

$msg = '';

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $logo = trim($_POST['logo_text']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $wa = trim($_POST['wa_number']);
    $hours_week = trim($_POST['opening_hours_week']);
    $hours_weekend = trim($_POST['opening_hours_weekend']);

    $sql = "UPDATE settings SET 
            logo_text = ?, email = ?, phone = ?, 
            address = ?, wa_number = ?, 
            opening_hours_week = ?, opening_hours_weekend = ? 
            WHERE id = 1";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$logo, $email, $phone, $address, $wa, $hours_week, $hours_weekend])) {
        $msg = "Pengaturan website berhasil diperbarui!";
        // Refresh
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
        $settings = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Web - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 min-h-screen flex">

    <!-- Sidebar (Simplified Sidebar for consistency) -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
        <div class="p-6 border-b border-gray-100 font-bold text-xl text-brand-red flex items-center gap-2">
             <i class="ph ph-hamburger text-brand-red"></i> Foody Admin
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-house-line text-xl"></i> Dashboard
            </a>
            <a href="manage_hero.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-image text-xl"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50 hover:text-rose-600 transition-all">
                <i class="ph ph-bowl-food text-xl"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-chat-centered-text text-xl"></i> Testimoni
            </a>
            <a href="manage_settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary bg-rose-50 text-rose-600 font-medium">
                <i class="ph-fill ph-gear text-xl"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="font-bold text-gray-800 text-lg">Konfigurasi Website Utama</h1>
        </header>

        <div class="p-8">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 shadow-sm border border-emerald-100 flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-xl"></i> <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 max-w-4xl space-y-8">
                <form method="POST" class="space-y-10">
                    
                    <section class="space-y-6">
                        <div class="flex items-center gap-4 text-gray-400">
                             <div class="h-px bg-gray-100 flex-1"></div>
                             <span class="text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Informasi Identitas</span>
                             <div class="h-px bg-gray-100 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Logo Website</label>
                                <input type="text" name="logo_text" value="<?php echo $settings['logo_text']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none">
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div class="flex items-center gap-4 text-gray-400">
                             <div class="h-px bg-gray-100 flex-1"></div>
                             <span class="text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Detail Kontak</span>
                             <div class="h-px bg-gray-100 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Publik</label>
                                <input type="email" name="email" value="<?php echo $settings['email']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Telepon/Telp Kantor</label>
                                <input type="text" name="phone" value="<?php echo $settings['phone']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">No. WhatsApp (Tanpa +)</label>
                                <input type="text" name="wa_number" value="<?php echo $settings['wa_number']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none" placeholder="62812xxx">
                            </div>
                        </div>
                        <div>
                             <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                             <textarea name="address" rows="2" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none"><?php echo $settings['address']; ?></textarea>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <div class="flex items-center gap-4 text-gray-400">
                             <div class="h-px bg-gray-100 flex-1"></div>
                             <span class="text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Jam Operasional</span>
                             <div class="h-px bg-gray-100 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Hari Kerja (Weekday)</label>
                                <input type="text" name="opening_hours_week" value="<?php echo $settings['opening_hours_week']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none" placeholder="Senin - Jumat: 10.00 - 22.00">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Akhir Pekan (Weekend)</label>
                                <input type="text" name="opening_hours_weekend" value="<?php echo $settings['opening_hours_weekend']; ?>" required 
                                    class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none" placeholder="Sabtu - Minggu: 08.00 - 23.00">
                            </div>
                        </div>
                    </section>

                    <div class="pt-6 border-t border-gray-100">
                         <button type="submit" 
                            class="bg-rose-500 text-white font-bold px-10 py-4 rounded-xl hover:bg-rose-600 transition-all shadow-xl shadow-rose-200 active:scale-95">
                            Simpan Seluruh Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
