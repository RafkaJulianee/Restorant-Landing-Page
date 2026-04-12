<?php
require_once '../../core/config.php';
check_login();

// Fetch settings for branding
$stmt_settings = $pdo->query("SELECT logo_text FROM settings WHERE id = 1");
$site_name = $stmt_settings->fetchColumn();

$msg = '';

// Fetch current hero content
$stmt = $pdo->query("SELECT * FROM hero_content WHERE id = 1");
$hero = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title1 = trim($_POST['title_1']);
    $title2 = trim($_POST['title_2']);
    $title_italic = trim($_POST['title_italic']);
    $subtitle = trim($_POST['subtitle']);
    $cta_primary = trim($_POST['cta_primary']);
    $cta_secondary = $hero['cta_secondary']; // Preserve original
    $discount = $hero['discount_text']; // Preserve original
    
    $image_path = $hero['main_image'];

    // Handle Image Upload
    if (!empty($_FILES['main_image']['name'])) {
        $target_dir = "../../assets/img/uploads/";
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_extension = strtolower(pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "hero_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)) {
            $image_path = "assets/img/uploads/" . $new_filename;
        } else {
            $msg = "Warning: Data tersimpan, namun gambar gagal diupload. Cek izin folder uploads.";
        }
    }

    $sql = "UPDATE hero_content SET 
            title_1 = ?, title_2 = ?, title_italic = ?, 
            subtitle = ?, cta_primary = ?, cta_secondary = ?, 
            main_image = ?, discount_text = ? 
            WHERE id = 1";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$title1, $title2, $title_italic, $subtitle, $cta_primary, $cta_secondary, $image_path, $discount])) {
        if (empty($msg)) $msg = "Konten Hero berhasil diperbarui!";
        // Refresh data
        $stmt = $pdo->query("SELECT * FROM hero_content WHERE id = 1");
        $hero = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hero - <?php echo $site_name; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Outfit', 'sans-serif'],
                        heading: ['"Playfair Display"', 'serif'],
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
                 <img src="../../assets/img/MyCode.png" alt="Logo" class="w-12 h-12 object-contain ml-1">
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
            <a href="../dashboard.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-house-line text-2xl"></i> Dashboard
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Kelola Konten</div>
            <a href="manage_hero.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-rose-50 text-brand-red font-bold transition-all border border-rose-100/50">
                <i class="ph-fill ph-image text-2xl"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-bowl-food text-2xl transition-transform group-hover:scale-110"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-chat-centered-text text-2xl transition-transform group-hover:scale-110"></i> Testimoni
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Sistem</div>
            <a href="manage_settings.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-gear text-2xl transition-transform group-hover:scale-110"></i> Pengaturan
            </a>
        </nav>

        <div class="p-6">
             <a href="../logout.php" class="flex items-center justify-center gap-3 px-6 py-4 rounded-2xl font-bold text-red-500 bg-red-50 hover:bg-red-100 transition-all active:scale-95">
                <i class="ph-bold ph-sign-out text-xl"></i> Keluar Sesi
             </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 flex items-center justify-between px-4 md:px-10 shrink-0">
            <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
                <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-xl transition-all shrink-0">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
                <div class="truncate">
                    <h1 class="text-lg md:text-xl font-serif font-bold text-gray-800 truncate">Manajemen Visual Hero</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider hidden lg:block">Atur tampilan utama website Anda</p>
                </div>
            </div>
             <a href="../../index.php" target="_blank" class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-brand-red bg-rose-50 px-3 md:px-4 py-2 rounded-full hover:bg-rose-100 transition-all border border-rose-100 shrink-0">
                <i class="ph ph-arrow-square-out"></i> <span class="hidden md:inline">Pratinjau Web</span>
             </a>
        </header>

        <div class="p-5 md:p-10">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-5 rounded-3xl mb-8 flex items-center gap-4 border border-emerald-100 shadow-sm animate-fade-in text-sm font-bold">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-200 shrink-0">
                        <i class="ph-bold ph-check text-xl"></i>
                    </div>
                    <div>
                        <p><?php echo $msg; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-sm border border-gray-100 lg:max-w-5xl">
                <form method="POST" enctype="multipart/form-data" class="space-y-10">
                    <!-- Heading Settings -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <i class="ph-fill ph-text-h-one text-brand-red text-2xl"></i>
                            <h3 class="font-serif font-bold text-lg text-gray-800">Pengaturan Judul & Teks</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Judul Utama (Fokus) (Maks 25 Karakter)</label>
                                <input type="text" name="title_italic" value="<?php echo $hero['title_italic']; ?>" required maxlength="25"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-heading italic text-brand-red text-xl">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Judul Baris 1 (Maks 25 Karakter)</label>
                                <input type="text" name="title_1" value="<?php echo $hero['title_1']; ?>" required maxlength="25"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Judul Baris 2 (Maks 25 Karakter)</label>
                                <input type="text" name="title_2" value="<?php echo $hero['title_2']; ?>" required maxlength="25"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                            </div>
                        </div>
                        <div class="mt-8">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Subjudul / Deskripsi (Maks 150 Karakter)</label>
                            <textarea name="subtitle" rows="4" required maxlength="150"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner text-gray-600 leading-relaxed"><?php echo $hero['subtitle']; ?></textarea>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="pt-10 border-t border-gray-50">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="ph-fill ph-cursor-click text-brand-red text-2xl"></i>
                            <h3 class="font-serif font-bold text-lg text-gray-800">Tombol Aksi</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Label Tombol Utama (Maks 25 Karakter)</label>
                                <input type="text" name="cta_primary" value="<?php echo $hero['cta_primary']; ?>" required maxlength="25"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="pt-10 border-t border-gray-50 flex flex-col md:flex-row gap-12">
                        <div class="w-full md:w-1/3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Gambar Saat Ini</label>
                            <div class="aspect-square rounded-[2rem] overflow-hidden border-[8px] border-gray-50 shadow-xl relative group">
                                <img id="hero_image_preview" src="../../<?php echo !empty($hero['main_image']) ? $hero['main_image'] : 'assets/img/hero_hd.png'; ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white pointer-events-none">
                                    <i class="ph ph-magnifying-glass-plus text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Ganti Gambar Piring Hero</label>
                            <div class="relative group">
                                <div class="w-full p-12 border-2 border-dashed border-gray-100 rounded-[2.5rem] text-center bg-gray-50 hover:bg-rose-50 hover:border-rose-200 transition-all cursor-pointer relative">
                                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <i class="ph ph-upload-simple text-3xl text-brand-red"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700 mb-1">Klik untuk pilih file gambar baru</p>
                                    <p class="text-xs text-gray-400">Rekomendasi: Gambar PNG transparan 1000x1000px</p>
                                    <input type="file" name="main_image" id="main_image_input" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" onchange="previewHeroImage(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                            class="w-full md:w-auto bg-brand-red text-white font-black px-12 py-5 rounded-2xl hover:bg-rose-600 transition-all shadow-xl shadow-brand-red/30 active:scale-95 flex items-center justify-center gap-3 text-lg uppercase tracking-widest">
                            <i class="ph-fill ph-floppy-disk text-2xl"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        function previewHeroImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('hero_image_preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
