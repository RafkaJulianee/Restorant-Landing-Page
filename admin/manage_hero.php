<?php
require_once '../config.php';
check_login();

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
    $cta_secondary = trim($_POST['cta_secondary']);
    $discount = trim($_POST['discount_text']);
    
    $image_path = $hero['main_image'];

    // Handle Image Upload
    if (!empty($_FILES['main_image']['name'])) {
        $target_dir = "../assets/img/";
        $file_extension = strtolower(pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "hero_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)) {
            $image_path = "assets/img/" . $new_filename;
        }
    }

    $sql = "UPDATE hero_content SET 
            title_1 = ?, title_2 = ?, title_italic = ?, 
            subtitle = ?, cta_primary = ?, cta_secondary = ?, 
            main_image = ?, discount_text = ? 
            WHERE id = 1";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$title1, $title2, $title_italic, $subtitle, $cta_primary, $cta_secondary, $image_path, $discount])) {
        $msg = "Konten Hero berhasil diperbarui!";
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
    <title>Kelola Hero - Admin Dashboard</title>
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50 hover:text-rose-600 transition-all">
                <i class="ph ph-house-line text-xl"></i> Dashboard
            </a>
            <a href="manage_hero.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 text-rose-600 font-medium">
                <i class="ph-fill ph-image text-xl"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50 hover:text-rose-600 transition-all">
                <i class="ph ph-bowl-food text-xl"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50 hover:text-rose-600 transition-all">
                <i class="ph ph-chat-centered-text text-xl"></i> Testimoni
            </a>
            <a href="manage_settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50 hover:text-rose-600 transition-all">
                <i class="ph ph-gear text-xl"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="font-bold text-gray-800">Manajemen Hero Section</h1>
             <a href="../index.php" target="_blank" class="text-sm text-rose-600 hover:underline flex items-center gap-1">
                Lihat Website <i class="ph ph-arrow-square-out"></i>
             </a>
        </header>

        <div class="p-8">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 flex items-center gap-3 border border-emerald-100 animate-fade-in shadow-sm">
                    <i class="ph-fill ph-check-circle text-2xl"></i> <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 max-w-4xl">
                <form method="POST" enctype="multipart/form-data" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul Utama (Baris 1)</label>
                            <input type="text" name="title_1" value="<?php echo $hero['title_1']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul Utama (Baris 2)</label>
                            <input type="text" name="title_2" value="<?php echo $hero['title_2']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Teks Miring (Fokus)</label>
                            <input type="text" name="title_italic" value="<?php echo $hero['title_italic']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all italic font-serif">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Teks Badge Diskon</label>
                            <input type="text" name="discount_text" value="<?php echo $hero['discount_text']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subjudul (Deskripsi)</label>
                        <textarea name="subtitle" rows="3" required 
                            class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all"><?php echo $hero['subtitle']; ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tombol Utama</label>
                            <input type="text" name="cta_primary" value="<?php echo $hero['cta_primary']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tombol Sekunder</label>
                            <input type="text" name="cta_secondary" value="<?php echo $hero['cta_secondary']; ?>" required 
                                class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-rose-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-bold text-gray-700 mb-3 text-center">Gambar Hero Saat Ini</label>
                            <div class="aspect-video rounded-2xl overflow-hidden border-4 border-white shadow-md">
                                <img src="../<?php echo $hero['main_image']; ?>" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Ganti Gambar Hero</label>
                            <div class="p-8 border-2 border-dashed border-gray-200 rounded-2xl text-center bg-slate-50 hover:bg-slate-100 transition-all cursor-pointer relative">
                                <i class="ph ph-upload-simple text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Klik untuk pilih file atau seret gambar ke sini</p>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                                <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        class="bg-rose-500 text-white font-bold px-10 py-4 rounded-2xl hover:bg-rose-600 transition-all shadow-xl shadow-rose-200 mt-6 active:scale-95">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
