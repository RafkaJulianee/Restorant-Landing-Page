<?php
session_start();
require_once '../core/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                $success = "Akun berhasil dibuat! Silakan login.";
            } else {
                $error = "Gagal membuat akun.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foody Admin - Registrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { background-color: #FEF8F0; }
        .brand-red { color: #F43F5E; }
        .bg-brand-red { background-color: #F43F5E; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-orange-50">
        <div class="p-8 pb-4 text-center">
            <div class="w-16 h-16 bg-brand-red text-white rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ph-fill ph-user-plus text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Daftarkan akun admin baru</p>
        </div>

        <div class="p-8 pt-4">
            <?php if($error): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 border border-red-100 flex items-center gap-2">
                    <i class="ph-bold ph-warning-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="bg-green-50 text-green-600 p-3 rounded-xl text-sm mb-6 border border-green-100 flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="username" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 outline-none transition-all"
                           placeholder="Pilih username">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <i class="ph ph-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 outline-none transition-all"
                           placeholder="Pilih password">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="confirm_password" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 outline-none transition-all"
                           placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-brand-red text-white font-bold py-3.5 rounded-xl hover:bg-rose-600 transition-colors shadow-lg shadow-brand-red/20 mt-2">
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500">Sudah punya akun? 
                    <a href="login.php" class="text-brand-red font-bold hover:underline">Login di sini</a>
                </p>
                <a href="../index.php" class="mt-4 text-sm text-gray-400 hover:text-brand-red flex items-center justify-center gap-2">
                    <i class="ph ph-arrow-left"></i> Kembali ke Website
                </a>
            </div>
        </div>
    </div>

</body>
</html>
