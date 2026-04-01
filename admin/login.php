<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foody Admin - Login</title>
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
                <i class="ph-fill ph-lock text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk mengelola konten</p>
        </div>

        <div class="p-8 pt-4">
            <?php if($error): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 border border-red-100 flex items-center gap-2">
                    <i class="ph-bold ph-warning-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="username" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 outline-none transition-all"
                           placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <i class="ph ph-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/10 outline-none transition-all"
                           placeholder="Masukkan password">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-brand-red text-white font-bold py-3.5 rounded-xl hover:bg-rose-600 transition-colors shadow-lg shadow-brand-red/20 mt-2">
                    Masuk ke Dashboard
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                <a href="../index.php" class="text-sm text-gray-400 hover:text-brand-red flex items-center justify-center gap-2">
                    <i class="ph ph-arrow-left"></i> Kembali ke Website
                </a>
            </div>
        </div>
    </div>

</body>
</html>
