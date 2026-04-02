<?php
require_once '../core/config.php';
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
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            red: '#F43F5E',
                            dark: '#1E293B',
                            light: '#F8FAFC'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-light font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 hidden md:flex flex-col sticky top-0 h-screen">
        <div class="p-8 flex items-center gap-3">
             <div class="bg-brand-red text-white p-2 rounded-2xl shadow-lg shadow-brand-red/20 ml-1">
                <i class="ph-fill ph-hamburger text-xl"></i>
             </div>
             <span class="font-serif font-black text-2xl text-gray-800 tracking-tight">Foody Central</span>
        </div>
        
        <nav class="flex-1 px-6 space-y-2 mt-4">
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mb-2">Utama</div>
            <a href="dashboard.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-rose-50 text-brand-red font-bold transition-all border border-rose-100/50">
                <i class="ph-fill ph-house-line text-2xl"></i> Dashboard
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Kelola Konten</div>
            <a href="modules/manage_hero.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-image text-2xl transition-transform group-hover:scale-110"></i> Kelola Hero
            </a>
            <a href="modules/manage_menu.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-bowl-food text-2xl transition-transform group-hover:scale-110"></i> Menu Makanan
            </a>
            <a href="modules/manage_testimonials.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-chat-centered-text text-2xl transition-transform group-hover:scale-110"></i> Testimoni
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Sistem</div>
            <a href="modules/manage_settings.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-gear text-2xl transition-transform group-hover:scale-110"></i> Pengaturan
            </a>
        </nav>

        <div class="p-6">
             <a href="logout.php" class="flex items-center justify-center gap-3 px-6 py-4 rounded-2xl font-bold text-red-500 bg-red-50 hover:bg-red-100 transition-all active:scale-95">
                <i class="ph-bold ph-sign-out text-xl"></i> Keluar Sesi
             </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 flex items-center justify-between px-10">
            <div>
                <h1 class="text-xl font-serif font-bold text-gray-800">Ringkasan Dashboard</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Informasi perkembangan restoran Anda</p>
            </div>
            <div class="flex items-center gap-6">
                <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-2 text-xs font-bold text-brand-red bg-rose-50 px-4 py-2 rounded-full hover:bg-rose-100 transition-all border border-rose-100">
                    <i class="ph ph-eye"></i> Lihat Web User
                </a>
                <div class="flex items-center gap-3 pl-6 border-l border-gray-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 leading-none mb-1">Administrator</p>
                        <p class="text-[10px] text-emerald-500 font-bold opacity-80 uppercase leading-none">Online Sekarang</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-brand-red text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-brand-red/20">A</div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-10 space-y-10">
            <!-- Welcome Message -->
            <div class="bg-gradient-to-br from-brand-dark to-slate-800 rounded-[2.5rem] p-12 text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="inline-block bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-6 border border-white/10">Selamat Datang Kembali</span>
                    <h2 class="text-4xl font-serif font-bold mb-4 leading-tight">Halo, Administrator! <br/> Kelola Restoran Anda Hari Ini.</h2>
                    <p class="text-slate-400 max-w-xl leading-relaxed text-sm">
                        Web Anda baru saja diperbarui ke versi premium. Sekarang Anda dapat mengelola elemen visual baru, menu, dan testimoni pelanggan dengan lebih mudah dan cepat.
                    </p>
                    <div class="mt-8 flex gap-4">
                        <a href="modules/manage_hero.php" class="bg-brand-red text-white px-6 py-3 rounded-xl font-bold text-sm hover:translate-y-1 transition-all shadow-lg shadow-brand-red/30">Cek Visual Hero</a>
                        <a href="modules/manage_menu.php" class="bg-white/10 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition-all backdrop-blur-md border border-white/10">Update Menu</a>
                    </div>
                </div>
                <i class="ph-fill ph-rocket-launch absolute right-12 top-1/2 -translate-y-1/2 text-[180px] text-white/5 -rotate-12 transition-transform group-hover:scale-110 duration-700"></i>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-bowl-food"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Menu Makanan</p>
                        <h3 class="text-3xl font-serif font-black text-gray-800 tracking-tight"><?php echo $total_menu; ?> <span class="text-sm font-normal text-gray-400">Item</span></h3>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                    <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-chat-centered-text"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Testimoni</p>
                        <h3 class="text-3xl font-serif font-black text-gray-800 tracking-tight"><?php echo $total_testimony; ?> <span class="text-sm font-normal text-gray-400">Ulasan</span></h3>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-lightning"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Status Sistem</p>
                        <h3 class="text-3xl font-serif font-black text-emerald-600 tracking-tight uppercase">Aktif</h3>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
