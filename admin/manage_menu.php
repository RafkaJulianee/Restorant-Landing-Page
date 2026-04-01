<?php
require_once '../config.php';
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
        $target_dir = "../assets/img/";
        $new_filename = "menu_" . time() . "_" . $_FILES["image"]["name"];
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_filename)) {
            $image_path = "assets/img/" . $new_filename;
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
    <title>Kelola Menu - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
        <div class="p-6 border-b border-gray-100 font-bold text-xl text-brand-red flex items-center gap-2">
             <i class="ph ph-hamburger"></i> Foody Admin
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-house-line text-xl"></i> Dashboard
            </a>
            <a href="manage_hero.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-image text-xl"></i> Kelola Hero
            </a>
            <a href="manage_menu.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 text-rose-600 font-medium">
                <i class="ph-fill ph-bowl-food text-xl"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-chat-centered-text text-xl"></i> Testimoni
            </a>
            <a href="manage_settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-gear text-xl"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="font-bold text-gray-800">Manajemen Menu Makanan</h1>
            <button onclick="openModal()" class="bg-rose-500 text-white text-sm font-bold px-5 py-2 rounded-xl hover:bg-rose-600 flex items-center gap-2">
                <i class="ph ph-plus"></i> Tambah Menu
            </button>
        </header>

        <div class="p-8">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 shadow-sm border border-emerald-100">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($menu_items as $item): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="../<?php echo $item['image']; ?>" class="w-12 h-12 rounded-full object-cover">
                                    <div>
                                        <div class="font-bold text-gray-800"><?php echo $item['name']; ?></div>
                                        <div class="text-xs text-gray-400"><?php echo $item['description']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-600">Rp <?php echo $item['price']; ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1 text-yellow-500 font-bold">
                                    <i class="ph-fill ph-star"></i> <?php echo $item['rating']; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-<?php echo $item['category_color']; ?>-100 text-<?php echo $item['category_color']; ?>-600">
                                    <?php echo $item['category_color']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="editItem(<?php echo htmlspecialchars(json_encode($item)); ?>)" class="text-blue-500 hover:text-blue-600"><i class="ph ph-pencil text-xl"></i></button>
                                <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Hapus menu ini?')" class="text-red-500 hover:text-red-600"><i class="ph ph-trash text-xl"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-xl overflow-hidden shadow-2xl animate-fade-in">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 id="modalTitle" class="font-bold text-gray-800">Tambah Menu Baru</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                <input type="hidden" name="id" id="formId">
                <input type="hidden" name="current_image" id="formCurrentImage">
                
                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Menu</label>
                        <input type="text" name="name" id="formName" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-rose-500">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Harga (contoh: 45k)</label>
                        <input type="text" name="price" id="formPrice" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Deskripsi Singkat</label>
                    <input type="text" name="description" id="formDesc" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-rose-500">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Rating (1.0 - 5.0)</label>
                        <input type="number" step="0.1" name="rating" id="formRating" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Warna Kategori</label>
                        <select name="category_color" id="formColor" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-rose-500">
                            <option value="pink">Pink</option>
                            <option value="orange">Orange</option>
                            <option value="green">Green</option>
                            <option value="purple">Purple</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Gambar Menu</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-rose-500 text-white font-bold py-3 rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-200">Simpan Menu</button>
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-500 font-bold">Batal</button>
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
