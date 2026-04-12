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
    <aside id="mobileSidebar" class="w-72 bg-white border-r border-gray-100 flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 h-screen">
        <div class="p-8 flex items-center justify-between gap-3 md:justify-start w-full">
             <div class="flex items-center gap-4">
                 <img src="../assets/img/MyCode.png" alt="Logo" class="w-12 h-12 object-contain ml-2">
                 <div class="flex flex-col">
                     <span class="font-serif font-black text-2xl text-gray-800 tracking-tight leading-none"><?php echo $site_name; ?></span>
                     <span class="text-[9px] text-brand-red font-bold uppercase tracking-[0.2em] mt-1">Dashboard Admin</span>
                 </div>
             </div>
             <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-brand-red">
                 <i class="ph ph-x text-2xl"></i>
             </button>
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

    <main class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 flex items-center justify-between px-4 md:px-10 shrink-0">
            <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
                <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-xl transition-all shrink-0">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <div class="truncate">
                    <h1 class="text-lg md:text-xl font-serif font-bold text-gray-800 truncate">Ringkasan Dashboard</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider hidden lg:block">Informasi perkembangan restoran Anda</p>
                </div>
            </div>
            <div class="flex items-center gap-3 md:gap-6">
                <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-2 text-[10px] md:text-xs font-bold text-brand-red bg-rose-50 px-3 md:px-4 py-2 rounded-full hover:bg-rose-100 transition-all border border-rose-100">
                    <i class="ph ph-eye"></i> <span class="hidden md:inline">Lihat Web User</span>
                </a>
                <div class="flex items-center gap-3 pl-3 md:pl-6 border-l border-gray-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 leading-none mb-1">Admin</p>
                        <p class="text-[9px] text-emerald-500 font-bold opacity-80 uppercase leading-none">Online</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-brand-red text-white flex items-center justify-center font-bold text-lg md:text-xl shadow-lg shadow-brand-red/20">A</div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-5 md:p-10 space-y-6 md:space-y-10">
            <!-- Welcome Message -->
            <div class="bg-gradient-to-br from-brand-dark to-slate-800 rounded-[2rem] md:rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="inline-block bg-white/10 backdrop-blur-md px-3 md:px-4 py-1.5 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-[0.2em] mb-4 md:mb-6 border border-white/10">Selamat Datang Kembali</span>
                    <h2 class="text-2xl md:text-4xl font-serif font-bold mb-3 md:mb-4 leading-tight">Halo, Administrator! <br class="hidden md:block"/> Kelola Restoran Anda.</h2>
                    <p class="text-slate-400 max-w-xl leading-relaxed text-xs md:text-sm">
                        Web Anda baru saja diperbarui ke versi premium. Sekarang Anda dapat mengelola elemen visual baru, menu, dan testimoni pelanggan dengan lebih mudah.
                    </p>
                    <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 md:gap-4">
                        <a href="modules/manage_hero.php" class="bg-brand-red text-white px-6 py-3 rounded-xl font-bold text-xs md:text-sm hover:translate-y-1 transition-all shadow-lg shadow-brand-red/30 text-center">Cek Visual Hero</a>
                        <a href="modules/manage_menu.php" class="bg-white/10 text-white px-6 py-3 rounded-xl font-bold text-xs md:text-sm hover:bg-white/20 transition-all backdrop-blur-md border border-white/10 text-center">Update Menu</a>
                    </div>
                </div>
                <i class="ph-fill ph-rocket-launch absolute -right-4 md:right-12 top-1/2 -translate-y-1/2 text-[120px] md:text-[180px] text-white/5 -rotate-12 transition-transform group-hover:scale-110 duration-700"></i>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5 md:gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-500 rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-bowl-food"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Menu Makanan</p>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-gray-800 tracking-tight"><?php echo $total_menu; ?> <span class="text-xs md:text-sm font-normal text-gray-400">Item</span></h3>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5 md:gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-amber-50 text-amber-500 rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-chat-centered-text"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Testimoni</p>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-gray-800 tracking-tight"><?php echo $total_testimony; ?> <span class="text-xs md:text-sm font-normal text-gray-400">Ulasan</span></h3>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5 md:gap-6 hover:shadow-xl hover:shadow-gray-200/50 transition-all group sm:col-span-2 lg:col-span-1">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-inner transition-transform group-hover:-rotate-6">
                        <i class="ph ph-lightning"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Status Sistem</p>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-emerald-600 tracking-tight uppercase">Aktif</h3>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>
