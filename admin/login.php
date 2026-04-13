<?php
session_start();
require_once '../core/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// Fetch settings for branding
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

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
    <title><?php echo $settings['logo_text']; ?> Admin - Login</title>
    <link rel="icon" type="image/png" href="../assets/img/MyCode.png">
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

    <div class="w-full h-full flex flex-col md:flex-row shadow-2xl overflow-hidden min-h-screen">
        
        <!-- Left Panel: Brand Accent -->
        <div class="w-full md:w-1/2 bg-indigo-600 p-8 sm:p-12 lg:p-20 flex flex-col items-center md:items-start justify-center text-white relative overflow-hidden min-h-[35vh] md:min-h-screen" style="background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-80 h-80 rounded-full border-[40px] border-white"></div>
                <div class="absolute bottom-[10%] right-[-5%] w-60 h-60 rounded-full border-[20px] border-white"></div>
            </div>

            <div class="relative z-10 space-y-6 md:space-y-8 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30">
                        <img src="../assets/img/MyCode.png" alt="Logo" class="w-6 h-6 md:w-8 md:h-8 object-contain brightness-0 invert">
                    </div>
                    <span class="text-xl md:text-2xl font-black tracking-tight uppercase"><?php echo $settings['logo_text']; ?></span>
                </div>

                <div class="space-y-4 max-w-md">
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight">Hey, Hello!</h1>
                    <p class="text-base md:text-lg lg:text-xl text-white/80 font-medium">Bantu kami mengelola website <?php echo $settings['logo_text']; ?> dengan lebih mudah.</p>
                </div>

                <p class="hidden md:block text-sm text-white/60 font-medium leading-relaxed max-w-sm pt-4 md:pt-12 border-t border-white/20 italic">
                    "Kami menyediakan alat yang menyederhanakan cara Anda mengelola menu dan testimoni pelanggan tanpa hambatan teknis."
                </p>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-6 sm:p-12 lg:p-20 relative">
            
            <div class="w-full max-w-[22rem] space-y-8 md:space-y-10">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Welcome Back</h2>
                    <p class="text-gray-500 font-medium text-sm md:text-base">Masuk untuk mengelola website anda.</p>
                </div>

                <?php if($error): ?>
                    <div class="bg-red-50 text-brand-red p-4 rounded-2xl text-xs border border-red-100 flex items-center gap-3 animate-shake">
                        <i class="ph-bold ph-warning-circle text-lg shrink-0"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6 md:space-y-8">
                    <div class="space-y-5 md:space-y-6">
                        <!-- Username -->
                        <div class="space-y-2">
                            <label class="hidden">Username</label>
                            <div class="relative group">
                                <i class="ph ph-user absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-brand-red transition-colors text-lg"></i>
                                <input type="text" name="username" required 
                                    class="w-full pl-16 pr-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/5 outline-none transition-all placeholder:text-gray-400 font-medium text-sm text-gray-700 shadow-inner"
                                    placeholder="Username">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="hidden">Password</label>
                            <div class="relative group">
                                <i class="ph ph-lock-simple absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-brand-red transition-colors text-lg"></i>
                                <input type="password" id="password" name="password" required 
                                    class="w-full pl-16 pr-16 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/5 outline-none transition-all placeholder:text-gray-400 font-medium text-sm text-gray-700 shadow-inner"
                                    placeholder="Password">
                                <button type="button" id="togglePassword" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-red transition-colors p-2">
                                    <i class="ph-bold ph-eye text-lg" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="#" class="text-xs font-bold text-indigo-600 hover:text-brand-red transition-colors" style="color: #F43F5E;">Forgot Password?</a>
                    </div>

                    <button type="submit" 
                        class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-opacity-90 transition-all active:scale-[0.98] shadow-xl shadow-indigo-600/20 text-sm tracking-wide"
                        style="background: #F43F5E; box-shadow: 0 10px 25px -5px rgba(244, 63, 94, 0.4);">
                        Sign In
                    </button>
                </form>

                <!-- Back Link -->
                <div class="mt-6 text-center">
                    <a href="../index.php" class="inline-flex items-center gap-2 text-xs text-gray-300 hover:text-brand-red transition-colors font-bold uppercase tracking-widest">
                        <i class="ph-bold ph-arrow-left"></i> Home
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
