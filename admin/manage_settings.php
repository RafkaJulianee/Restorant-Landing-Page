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
    <title>Pengaturan Web - Foody Central</title>
    
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
            <a href="manage_testimonials.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-chat-centered-text text-2xl transition-transform group-hover:scale-110"></i> Testimoni
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Sistem</div>
            <a href="manage_settings.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-rose-50 text-brand-red font-bold transition-all border border-rose-100/50">
                <i class="ph-fill ph-gear text-2xl"></i> Pengaturan
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
                <h1 class="text-xl font-serif font-bold text-gray-800">Pengaturan Umum Website</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Konfigurasi identitas dan kontak restoran</p>
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

            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 lg:max-w-5xl">
                <form method="POST" class="space-y-12">
                    
                    <!-- Identity Section -->
                    <section class="space-y-8">
                         <div class="flex items-center gap-3">
                            <i class="ph-fill ph-identification-card text-brand-red text-2xl"></i>
                            <h3 class="font-serif font-bold text-lg text-gray-800">Identitas Restoran</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Logo / Brand</label>
                                <input type="text" name="logo_text" value="<?php echo $settings['logo_text']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                            </div>
                        </div>
                    </section>

                    <!-- Contact Details -->
                    <section class="space-y-8 pt-10 border-t border-gray-50">
                        <div class="flex items-center gap-3">
                            <i class="ph-fill ph-phone-call text-brand-red text-2xl"></i>
                            <h3 class="font-serif font-bold text-lg text-gray-800">Informasi Kontak & Lokasi</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Email Bisnis</label>
                                <input type="email" name="email" value="<?php echo $settings['email']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-medium text-gray-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Telepon Kantor</label>
                                <input type="text" name="phone" value="<?php echo $settings['phone']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-medium text-gray-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">WhatsApp (Format: 62812...)</label>
                                <input type="text" name="wa_number" value="<?php echo $settings['wa_number']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-medium text-gray-700">
                            </div>
                        </div>
                        <div>
                             <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Alamat Lengkap (Terhubung ke Google Maps)</label>
                             <textarea name="address" rows="3" required 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner text-gray-600 leading-relaxed"><?php echo $settings['address']; ?></textarea>
                             <p class="text-[10px] text-gray-400 mt-3 flex items-center gap-2">
                                <i class="ph-bold ph-info"></i> Ubah alamat ini untuk memperbarui pin lokasi pada peta di halaman depan.
                             </p>
                        </div>
                    </section>

                    <!-- Operating Hours -->
                    <section class="space-y-8 pt-10 border-t border-gray-50">
                        <div class="flex items-center gap-3">
                            <i class="ph-fill ph-clock text-brand-red text-2xl"></i>
                            <h3 class="font-serif font-bold text-lg text-gray-800">Jam Operasional</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Senin - Jumat (Weekday)</label>
                                <input type="text" name="opening_hours_week" value="<?php echo $settings['opening_hours_week']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner text-gray-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Sabtu - Minggu (Weekend)</label>
                                <input type="text" name="opening_hours_weekend" value="<?php echo $settings['opening_hours_weekend']; ?>" required 
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner text-gray-700">
                            </div>
                        </div>
                    </section>

                    <div class="pt-6 border-t border-gray-100">
                         <button type="submit" 
                            class="w-full md:w-auto bg-brand-red text-white font-black px-12 py-5 rounded-2xl hover:bg-rose-600 transition-all shadow-xl shadow-brand-red/30 active:scale-95 flex items-center justify-center gap-3 text-lg uppercase tracking-widest">
                            <i class="ph-fill ph-floppy-disk text-2xl"></i>
                            Simpan Seluruh Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
