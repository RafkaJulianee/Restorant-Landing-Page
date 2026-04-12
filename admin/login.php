<?php
session_start();
require_once '../core/config.php';

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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            light: '#FEF8F0',
                            red: '#F43F5E',
                            orange: '#F97316',
                            dark: '#1E293B',
                            gray: '#64748B'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #FEF8F0; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 lg:p-8 font-sans antialiased relative">

    <!-- Abstract Background Decor -->
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-brand-red/5 rounded-full blur-[100px] -z-10 pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-[500px] h-[500px] bg-brand-orange/5 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

    <div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-xl overflow-hidden flex flex-col md:flex-row border border-orange-50/50">
        
        <!-- Left Panel (Gradient Illustration) -->
        <div class="hidden md:flex md:w-[45%] bg-gradient-to-br from-brand-red via-[#ff5b77] to-brand-orange p-10 lg:p-14 flex-col justify-between relative overflow-hidden">
            <!-- Icon -->
            <div class="text-white relative z-10">
                <i class="ph-bold ph-asterisk text-5xl opacity-90 hover:rotate-90 transition-transform duration-700"></i>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 mt-auto">
                <p class="text-white/80 font-medium mb-3 tracking-wide text-sm">Sangat mudah</p>
                <h2 class="text-4xl lg:text-[2.75rem] font-bold leading-[1.15] text-white">
                    Akses kontrol panel<br/>untuk manajemen<br/>yang lebih baik
                </h2>
            </div>
            
            <!-- Abstract Gradients -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-white/20 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-black/20 rounded-full blur-[80px] pointer-events-none"></div>
        </div>

        <!-- Right Panel (Form) -->
        <div class="w-full md:w-[55%] p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative">

            <!-- Mobile Icon -->
            <div class="md:hidden text-brand-red mb-8">
                <i class="ph-bold ph-asterisk text-4xl"></i>
            </div>

            <!-- Title -->
            <div class="mb-8 text-brand-red hidden md:block">
                <i class="ph-bold ph-asterisk text-4xl hover:rotate-90 transition-transform duration-700"></i>
            </div>
            
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3 tracking-tight">Login Admin</h2>
            <p class="text-gray-500 mb-10 text-sm sm:text-base leading-relaxed max-w-md">Silakan masuk untuk mengelola konten, pesanan, dan pengaturan website Anda.</p>

            <?php if($error): ?>
                <div class="bg-red-50 text-brand-red p-4 rounded-xl text-sm mb-8 border border-red-100 flex items-center gap-3">
                    <i class="ph-bold ph-warning-circle text-xl"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6 max-w-md w-full">
                <!-- Username -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Username</label>
                    <input type="text" name="username" required 
                       class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all placeholder-gray-400 font-medium text-gray-800"
                       placeholder="Masukkan username">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                           class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all placeholder-gray-400 font-medium text-gray-800"
                           placeholder="••••••••••••">
                        <button type="button" id="togglePassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-red transition-colors focus:outline-none flex items-center justify-center p-1">
                            <i class="ph-bold ph-eye text-xl" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-brand-red text-white font-bold py-4 rounded-xl hover:bg-rose-600 transition-all active:scale-[0.98] shadow-lg shadow-brand-red/30 mt-4 text-base">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Bottom Links -->
            <div class="mt-12 text-center max-w-md w-full border-t border-gray-100 pt-8">
                <p class="text-sm text-gray-500 font-medium">Belum punya akun? <a href="register.php" class="text-brand-red hover:underline font-bold">Daftar di sini</a></p>
                <div class="mt-4">
                    <a href="../index.php" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-brand-red transition-colors font-medium">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Website
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (type === 'password') {
                eyeIcon.classList.remove('ph-eye-slash');
                eyeIcon.classList.add('ph-eye');
            } else {
                eyeIcon.classList.remove('ph-eye');
                eyeIcon.classList.add('ph-eye-slash');
            }
        });
    </script>
</body>
</html>
