<?php
require_once '../config.php';
check_login();

// Fetch statistics
$stmt_menu = $pdo->query("SELECT COUNT(*) FROM menu_items");
$total_menu = $stmt_menu->fetchColumn();

$stmt_testimony = $pdo->query("SELECT COUNT(*) FROM testimonials");
$total_testimony = $stmt_testimony->fetchColumn();

$stmt_settings = $pdo->query("SELECT logo_text FROM settings WHERE id = 1");
$site_name = $stmt_settings->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - <?php echo $site_name; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { background-color: #f8fafc; }
        .sidebar-link { color: #64748b; transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { color: #F43F5E; background-color: #fff1f2; }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
             <div class="bg-rose-500 text-white p-1.5 rounded-lg"><i class="ph-fill ph-hamburger"></i></div>
             <span class="font-bold text-xl text-gray-800">Foody Admin</span>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="dashboard.php" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl font-medium">
                <i class="ph-fill ph-house-line text-xl"></i> Dashboard
            </a>
            <div class="pt-4 pb-2 px-4 text-[10px] uppercase font-bold text-gray-400 tracking-wider">Konten Web</div>
            <a href="manage_hero.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium">
                <i class="ph ph-image text-xl"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium">
                <i class="ph ph-bowl-food text-xl"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium">
                <i class="ph ph-chat-centered-text text-xl"></i> Testimoni
            </a>
            <div class="pt-4 pb-2 px-4 text-[10px] uppercase font-bold text-gray-400 tracking-wider">Lainnya</div>
            <a href="manage_settings.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium">
                <i class="ph ph-gear text-xl"></i> Pengaturan Web
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
             <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-red-500 hover:bg-red-50 transition-all">
                <i class="ph ph-sign-out text-xl"></i> Keluar
             </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="text-lg font-bold text-gray-800">Ringkasan Dashboard</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">Halo, <strong>Admin</strong></span>
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold">A</div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-8 space-y-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph ph-bowl-food"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Total Menu</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_menu; ?> Item</h3>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph ph-chat-centered-text"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Total Testimoni</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_testimony; ?> Ulasan</h3>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph ph-eye"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Status Web</p>
                        <h3 class="text-2xl font-bold text-emerald-600">Online</h3>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="bg-gradient-to-r from-rose-500 to-orange-500 rounded-3xl p-10 text-white shadow-xl shadow-rose-200 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold mb-3">Selamat Datang di Foody CMS</h2>
                    <p class="text-rose-50 max-w-lg leading-relaxed">
                        Anda dapat mengelola seluruh konten website dengan mudah melalui panel ini. Pilih menu di samping untuk mulai memperbarui informasi, menu makanan, atau melihat ulasan pelanggan.
                    </p>
                </div>
                <i class="ph ph-bowl-food absolute -right-10 -bottom-10 text-[200px] text-white/10 rotate-12"></i>
            </div>
        </div>
    </main>

</body>
</html>
