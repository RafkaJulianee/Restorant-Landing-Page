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
    <title>Kelola Testimoni - Admin Dashboard</title>
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
            <a href="manage_menu.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-bowl-food text-xl"></i> Menu Makanan
            </a>
            <a href="manage_testimonials.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary bg-rose-50 text-rose-600 font-medium">
                <i class="ph-fill ph-chat-centered-text text-xl"></i> Testimoni
            </a>
            <a href="manage_settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-rose-50">
                <i class="ph ph-gear text-xl"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="font-bold text-gray-800">Manajemen Ulasan & Testimoni</h1>
        </header>

        <div class="p-8">
            <?php if($msg): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 shadow-sm border border-emerald-100">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-6">
                <?php if(empty($testimonials)): ?>
                    <div class="bg-white p-12 text-center rounded-3xl border-2 border-dashed border-gray-100 italic text-gray-400">
                        Belum ada ulasan yang masuk.
                    </div>
                <?php endif; ?>

                <?php foreach($testimonials as $t): ?>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 items-start">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-bold text-slate-400 shrink-0">
                        <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg"><?php echo $t['name']; ?></h3>
                                <p class="text-xs text-gray-400"><?php echo $t['role']; ?> &bull; <?php echo date('d M Y', strtotime($t['created_at'])); ?></p>
                            </div>
                            <div class="flex gap-1 text-yellow-400">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="ph-fill ph-star <?php echo $i <= $t['rating'] ? '' : 'text-gray-100'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed italic">"<?php echo $t['comment']; ?>"</p>
                        
                        <div class="pt-4 flex flex-wrap gap-3 items-center justify-between">
                            <div class="flex items-center gap-2">
                                <?php if($t['is_approved']): ?>
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-full border border-emerald-100 flex items-center gap-1">
                                        <i class="ph-fill ph-check-circle"></i> Muncul di Web
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-bold uppercase rounded-full border border-gray-100 flex items-center gap-1">
                                        <i class="ph-fill ph-prohibit"></i> Tidak Muncul
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <?php if($t['is_approved']): ?>
                                    <a href="?action=reject&id=<?php echo $t['id']; ?>" class="bg-amber-50 text-amber-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-amber-100 transition-colors">Sembunyikan</a>
                                <?php else: ?>
                                    <a href="?action=approve&id=<?php echo $t['id']; ?>" class="bg-emerald-50 text-emerald-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-emerald-100 transition-colors">Tampilkan</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $t['id']; ?>" onclick="return confirm('Hapus ulasan ini secara permanen?')" class="bg-rose-50 text-rose-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-rose-100 transition-colors">Hapus</a>
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
