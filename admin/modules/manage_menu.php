<?php
require_once '../../core/config.php';
check_login();

$msg = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    if ($stmt->execute([$id])) {
        $msg = "Item menu berhasil dihapus!";
    }
}

// Handle Add/Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = trim($_POST['price']);
    $rating = trim($_POST['rating']);
    $color = trim($_POST['category_color']);
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    $image_path = isset($_POST['current_image']) ? $_POST['current_image'] : '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../assets/img/uploads/";
        $new_filename = "menu_" . time() . "_" . $_FILES["image"]["name"];
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_filename)) {
            $image_path = "assets/img/uploads/" . $new_filename;
        }
    }

    if ($id) {
        // Update
        $sql = "UPDATE menu_items SET name=?, description=?, price=?, rating=?, image=?, category_color=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $desc, $price, $rating, $image_path, $color, $id]);
        $msg = "Menu berhasil diperbarui!";
    } else {
        // Insert
        $sql = "INSERT INTO menu_items (name, description, price, rating, image, category_color) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $desc, $price, $rating, $image_path, $color]);
        $msg = "Menu baru berhasil ditambahkan!";
    }
}

// Fetch all menu items
$menu_items = $pdo->query("SELECT * FROM menu_items ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Foody Central</title>
    
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
            <a href="../dashboard.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-house-line text-2xl"></i> Dashboard
            </a>
            
            <div class="px-4 py-3 text-[10px] uppercase font-black text-gray-400 tracking-[0.2em] mt-8 mb-2">Kelola Konten</div>
            <a href="manage_hero.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-brand-red transition-all">
                <i class="ph ph-image text-2xl transition-transform group-hover:scale-110"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-rose-50 text-brand-red font-bold transition-all border border-rose-100/50">
                <i class="ph-fill ph-bowl-food text-2xl"></i> Menu Makanan
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

    <main class="flex-1">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 flex items-center justify-between px-10">
            <div>
                <h1 class="text-xl font-serif font-bold text-gray-800">Manajemen Menu Makanan</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kelola daftar hidangan restoran Anda</p>
            </div>
            <button onclick="openModal()" class="bg-brand-red text-white text-xs font-black px-6 py-3 rounded-xl hover:bg-rose-600 transition-all shadow-lg shadow-brand-red/30 flex items-center gap-2 uppercase tracking-widest">
                <i class="ph-bold ph-plus"></i> Tambah Menu
            </button>
        </header>

        <div class="p-10">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-5 rounded-3xl mb-8 flex items-center gap-4 border border-emerald-100 shadow-sm animate-fade-in">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="ph-bold ph-check text-xl"></i>
                    </div>
                    <p class="font-bold"><?php echo $msg; ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Item & Deskripsi</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Harga</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Rating</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kategori</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($menu_items as $item): ?>
                            <tr class="group hover:bg-brand-light transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative">
                                            <img src="../../<?php echo !empty($item['image']) ? $item['image'] : 'assets/img/menu/menu_placeholder.png'; ?>" 
                                                 class="w-16 h-16 rounded-2xl object-cover shadow-md group-hover:rotate-6 transition-transform">
                                            <div class="absolute inset-0 rounded-2xl bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div>
                                            <div class="font-serif font-bold text-gray-800 text-lg mb-0.5 group-hover:text-brand-red transition-colors"><?php echo $item['name']; ?></div>
                                            <div class="text-xs text-gray-400 font-medium max-w-xs line-clamp-1 italic"><?php echo $item['description']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-700 font-bold text-sm tracking-tight">Rp <?php echo $item['price']; ?></span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-1.5 text-amber-500 font-black text-lg">
                                        <i class="ph-fill ph-star"></i> 
                                        <span class="text-gray-700 font-bold"><?php echo $item['rating']; ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-<?php echo $item['category_color']; ?>-50 text-<?php echo $item['category_color']; ?>-500 border border-<?php echo $item['category_color']; ?>-100">
                                        <?php echo $item['category_color']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button onclick='editItem(<?php echo json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' 
                                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100 transition-all active:scale-90" title="Edit Item">
                                            <i class="ph-bold ph-pencil-simple"></i>
                                        </button>
                                        <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Hapus menu ini?')" 
                                           class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-all active:scale-90" title="Hapus Item">
                                            <i class="ph-bold ph-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl animate-fade-in border border-white/20">
            <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                   <h2 id="modalTitle" class="font-serif font-black text-2xl text-gray-800">Tambah Menu Baru</h2>
                   <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Lengkapi detail hidangan</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-white text-gray-400 hover:text-brand-red flex items-center justify-center shadow-sm transition-all"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                <input type="hidden" name="id" id="formId">
                <input type="hidden" name="current_image" id="formCurrentImage">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Menu</label>
                        <input type="text" name="name" id="formName" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Harga (Contoh: 45k)</label>
                        <input type="text" name="price" id="formPrice" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Singkat</label>
                    <textarea name="description" id="formDesc" required rows="2" 
                              class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner text-gray-600 leading-relaxed italic"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Rating (1.0 - 5.0)</label>
                        <div class="relative">
                            <input type="number" step="0.1" name="rating" id="formRating" required 
                                   class="w-full px-12 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700">
                            <i class="ph-fill ph-star absolute left-5 top-1/2 -translate-y-1/2 text-amber-500 text-lg"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Warna Kategori</label>
                        <select name="category_color" id="formColor" 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red outline-none transition-all shadow-inner font-bold text-gray-700 appearance-none">
                            <option value="pink">Pink (Utama)</option>
                            <option value="orange">Orange (Pedas/Hangat)</option>
                            <option value="green">Green (Healthy/Salad)</option>
                            <option value="purple">Purple (Minuman/Dessert)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Ganti Gambar Menu</label>
                    <div class="relative group">
                         <div class="w-full p-6 border-2 border-dashed border-gray-100 rounded-2xl text-center bg-gray-50 group-hover:bg-rose-50 group-hover:border-rose-200 transition-all">
                             <p class="text-xs font-bold text-gray-400">Klik untuk upload gambar hidangan (PNG/JPG)</p>
                             <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
                         </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-brand-red text-white font-black py-5 rounded-2xl hover:bg-rose-600 shadow-xl shadow-brand-red/30 transition-all active:scale-95 uppercase tracking-widest">
                        Simpan Menu
                    </button>
                    <button type="button" onclick="closeModal()" 
                            class="px-8 py-5 rounded-2xl bg-slate-100 text-slate-500 font-bold hover:bg-slate-200 transition-all uppercase tracking-widest text-xs">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        
        function openModal() {
            modal.classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Menu Baru';
            document.getElementById('formId').value = '';
            document.getElementById('formName').value = '';
            document.getElementById('formPrice').value = '';
            document.getElementById('formDesc').value = '';
            document.getElementById('formRating').value = '';
            document.getElementById('formCurrentImage').value = '';
            document.getElementById('formColor').value = 'pink';
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function editItem(item) {
            modal.classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Menu Makanan';
            document.getElementById('formId').value = item.id;
            document.getElementById('formName').value = item.name;
            document.getElementById('formPrice').value = item.price;
            document.getElementById('formDesc').value = item.description;
            document.getElementById('formRating').value = item.rating;
            document.getElementById('formColor').value = item.category_color;
            document.getElementById('formCurrentImage').value = item.image;
        }
    </script>

</body>
</html>
