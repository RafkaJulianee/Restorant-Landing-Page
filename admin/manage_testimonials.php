<?php
require_once '../config.php';
check_login();

$msg = '';

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] == 'approve') {
        $stmt = $pdo->prepare("UPDATE testimonials SET is_approved = 1 WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Testimoni disetujui!";
    } elseif ($_GET['action'] == 'reject') {
        $stmt = $pdo->prepare("UPDATE testimonials SET is_approved = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Testimoni ditolak!";
    } elseif ($_GET['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Testimoni dihapus!";
    }
}

// Fetch all testimonials
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Testimoni - Foody Central</title>
    
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
            <a href="dashboard.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-house-line text-2xl"></i> Dashboard
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Kelola Konten</div>
            <a href="manage_hero.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-image text-2xl transition-transform group-hover:scale-110"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-bowl-food text-2xl transition-transform group-hover:scale-110"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-rose-50 text-brand-red font-bold transition-all border border-rose-100/50">
                <i class="ph-fill ph-chat-centered-text text-2xl"></i> Testimoni
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Sistem</div>
            <a href="manage_settings.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-gear text-2xl transition-transform group-hover:scale-110"></i> Pengaturan
            </a>
        </nav>

        <div class="p-6">
             <a href="logout.php" class="flex items-center justify-center gap-3 px-6 py-4 rounded-2xl font-bold text-red-500 bg-red-50 hover:bg-red-100 transition-all active:scale-95">
                <i class="ph-bold ph-sign-out text-xl"></i> Keluar Sesi
             </a>
        </div>
    </aside>

    <main class="flex-1">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 flex items-center justify-between px-10">
            <div>
                <h1 class="text-xl font-serif font-bold text-gray-800">Ulasan & Testimoni</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Moderasi feedback pelanggan Anda</p>
            </div>
             <a href="../index.php" target="_blank" class="flex items-center gap-2 text-xs font-bold text-brand-red bg-rose-50 px-4 py-2 rounded-full hover:bg-rose-100 transition-all border border-rose-100">
                <i class="ph ph-arrow-square-out"></i> Pratinjau Web
             </a>
        </header>

        <div class="p-10">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-5 rounded-3xl mb-8 flex items-center gap-4 border border-emerald-100 shadow-sm animate-fade-in">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="ph-bold ph-check text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold">Berhasil!</p>
                        <p class="text-sm opacity-80"><?php echo $msg; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-8 lg:max-w-5xl">
                <?php if(empty($testimonials)): ?>
                    <div class="bg-white p-20 text-center rounded-[3rem] border-4 border-dashed border-gray-50 flex flex-col items-center gap-6">
                         <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl">
                            <i class="ph ph-chat-centered-dots"></i>
                         </div>
                         <div>
                            <h3 class="font-serif font-bold text-xl text-gray-400">Belum Ada Ulasan</h3>
                            <p class="text-sm text-gray-300 mt-1">Feedback dari pengguna akan muncul di sini</p>
                         </div>
                    </div>
                <?php endif; ?>

                <?php foreach($testimonials as $t): ?>
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col md:flex-row gap-8 items-start group">
                    <div class="w-20 h-20 rounded-[1.5rem] bg-gradient-to-br from-brand-red to-orange-400 flex items-center justify-center text-3xl font-black text-white shadow-xl shadow-brand-red/20 shrink-0 transform group-hover:rotate-3 transition-transform">
                        <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                    </div>
                    <div class="flex-1 space-y-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-serif font-bold text-gray-800 text-xl group-hover:text-brand-red transition-colors"><?php echo $t['name']; ?></h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1 opacity-70"><?php echo $t['role']; ?> &bull; <span class="text-slate-300"><?php echo date('d M Y', strtotime($t['created_at'])); ?></span></p>
                            </div>
                            <div class="flex gap-1.5 text-amber-400 text-lg">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="ph-fill ph-star <?php echo $i <= $t['rating'] ? '' : 'text-slate-100'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="relative bg-slate-50/50 p-6 rounded-2xl border border-gray-100/50">
                            <i class="ph-fill ph-quotes absolute -top-4 -left-2 text-4xl text-brand-red opacity-10"></i>
                            <p class="text-gray-600 leading-relaxed italic font-medium">"<?php echo $t['comment']; ?>"</p>
                        </div>
                        
                        <div class="pt-2 flex flex-wrap gap-4 items-center justify-between">
                            <div class="flex items-center gap-3">
                                <?php if($t['is_approved']): ?>
                                    <span class="px-5 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-100 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                        Live di Website
                                    </span>
                                <?php else: ?>
                                    <span class="px-5 py-2 bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-gray-100 flex items-center gap-2">
                                        <i class="ph-bold ph-eye-slash"></i> Tersembunyi
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-3">
                                <?php if($t['is_approved']): ?>
                                    <a href="?action=reject&id=<?php echo $t['id']; ?>" class="bg-amber-50 text-amber-600 text-xs font-black px-6 py-3 rounded-xl hover:bg-amber-100 transition-all uppercase tracking-widest">Sembunyikan</a>
                                <?php else: ?>
                                    <a href="?action=approve&id=<?php echo $t['id']; ?>" class="bg-emerald-50 text-emerald-600 text-xs font-black px-6 py-3 rounded-xl hover:bg-emerald-100 transition-all uppercase tracking-widest">Tampilkan</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $t['id']; ?>" onclick="return confirm('Hapus ulasan ini secara permanen?')" class="bg-red-50 text-red-500 text-xs font-black px-6 py-3 rounded-xl hover:bg-red-100 transition-all uppercase tracking-widest">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

</body>
</html>

</body>
</html>
