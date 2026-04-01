<?php
require_once 'config.php';

// Fetch Site Settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

// Fetch Hero Content
$stmt = $pdo->query("SELECT * FROM hero_content WHERE id = 1");
$hero = $stmt->fetch();

// Fetch Features
$stmt = $pdo->query("SELECT * FROM features");
$features = $stmt->fetchAll();

// Fetch Menu Items
$stmt = $pdo->query("SELECT * FROM menu_items");
$menu_items = $stmt->fetchAll();

// Fetch Approved Testimonials
$stmt = $pdo->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC");
$testimonials = $stmt->fetchAll();

// Handle Review Submission (AJAX or Post)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    header('Content-Type: application/json');
    $name = trim($_POST['name']);
    $role = ""; // Profesi dihapus sesuai permintaan
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if (empty($name) || empty($comment) || $rating < 1) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO testimonials (name, role, rating, comment, is_approved) VALUES (?, ?, ?, ?, 1)");
    if ($stmt->execute([$name, $role, $rating, $comment])) {
        echo json_encode(['status' => 'success', 'message' => 'Terima kasih atas ulasan Anda!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim ulasan.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/MyCode.png" type="image/x-icon">
    <title><?php echo $settings['logo_text']; ?> - Makanan Sehat & Lezat</title>
    
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
                            light: '#FEF8F0',
                            red: '#F43F5E',
                            orange: '#F97316',
                            green: '#22C55E',
                            dark: '#1E293B',
                            gray: '#64748B'
                        }
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="font-sans antialiased overflow-x-hidden relative">

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/<?php echo $settings['wa_number']; ?>?text=Halo%20<?php echo $settings['logo_text']; ?>,%20saya%20ingin%20memesan%20makanan!" target="_blank" rel="noopener noreferrer" 
       class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:bg-[#1ebe57] hover:scale-110 transition-all duration-300 flex items-center justify-center group">
        <i class="ph-fill ph-whatsapp-logo text-3xl"></i>
        <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 ease-in-out font-medium group-hover:ml-2 group-hover:mr-1">Pesan via WA</span>
    </a>

    <!-- Navbar -->
    <nav class="fixed w-full z-40 glass transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-2">
                <div class="bg-brand-red text-white p-1.5 rounded-full">
                    <i class="ph-fill ph-hamburger text-xl"></i>
                </div>
                <span class="font-bold text-2xl text-brand-red tracking-tight"><?php echo $settings['logo_text']; ?></span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 items-center font-medium text-brand-dark">
                <a href="#home" class="hover:text-brand-red transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-brand-red after:transition-all hover:after:w-full">Beranda</a>
                <a href="#about" class="hover:text-brand-red transition-colors">Tentang Kami</a>
                <a href="#menu" class="hover:text-brand-red transition-colors">Menu</a>
                <a href="#testimonials" class="hover:text-brand-red transition-colors">Testimoni</a>
                <a href="#contact" class="hover:text-brand-red transition-colors">Kontak</a>
            </div>

            <!-- Right Actions (WhatsApp Only) -->
            <div class="hidden md:flex items-center space-x-5">
                <a href="https://wa.me/<?php echo $settings['wa_number']; ?>" target="_blank" class="bg-brand-red text-white px-6 py-2.5 rounded-full font-bold shadow-lg shadow-brand-red/20 hover:bg-rose-600 transition-all hover:scale-105 active:scale-95">
                    Pesan Sekarang
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-btn" class="md:hidden text-brand-dark text-3xl">
                <i class="ph ph-list"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-white shadow-lg border-t border-gray-100 py-4 flex flex-col px-6 gap-4 pb-6">
            <a href="#home" class="font-medium text-lg border-b border-gray-50 pb-2">Beranda</a>
            <a href="#about" class="font-medium text-lg border-b border-gray-50 pb-2">Tentang Kami</a>
            <a href="#menu" class="font-medium text-lg border-b border-gray-50 pb-2">Menu</a>
            <a href="#testimonials" class="font-medium text-lg border-b border-gray-50 pb-2">Testimoni</a>
            <a href="#contact" class="font-medium text-lg border-b border-gray-50 pb-2">Kontak</a>
        </div>
    </nav>

    <!-- Hero Section (New Concept: Premium Dining) -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-44 lg:pb-32 overflow-hidden bg-brand-light">
        <!-- Modern Decorative Elements -->
        <div class="absolute -top-24 -right-24 w-[450px] h-[450px] bg-brand-red/10 rounded-full blur-[100px] -z-10"></div>
        <div class="absolute -bottom-24 -left-24 w-[350px] h-[350px] bg-brand-orange/10 rounded-full blur-[80px] -z-10"></div>
        
        <!-- Abstract Shape Decor -->
        <div class="absolute top-1/4 right-0 opacity-10 pointer-events-none -z-10">
            <svg width="400" height="400" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M150 100C150 138.66 118.66 170 80 170C41.3401 170 10 138.66 10 100C10 61.3401 41.3401 30 80 30C118.66 30 150 61.3401 150 100Z" fill="#F43F5E"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-4">
                
                <!-- Hero Text (Left) -->
                <div class="w-full lg:w-[55%] flex flex-col items-center lg:items-start text-center lg:text-left z-20">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-orange-100 shadow-sm mb-6 animate-fade-in-down">
                        <span class="flex h-2 w-2 rounded-full bg-brand-red animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-dark/70"><?php echo $hero['discount_text']; ?></span>
                    </div>

                    <h1 class="font-heading text-5xl sm:text-6xl lg:text-[5.5rem] font-bold leading-[1] mb-6 text-brand-dark tracking-tight">
                        <?php echo $hero['title_1']; ?> <br/>
                        <span class="relative inline-block text-brand-red italic">
                            <?php echo $hero['title_italic']; ?>
                            <svg class="absolute -bottom-2 left-0 w-full h-3 text-orange-200 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 50 0 100 5" stroke="currentColor" stroke-width="4" fill="transparent" />
                            </svg>
                        </span> <br/>
                        <?php echo $hero['title_2']; ?>
                    </h1>

                    <p class="text-brand-gray text-lg lg:text-xl mb-10 max-w-xl leading-relaxed font-light">
                        <?php echo $hero['subtitle']; ?>
                    </p>

                    <div class="flex flex-col sm:flex-row gap-6 items-center justify-center lg:justify-start w-full">
                        <a href="#menu" class="group relative w-full sm:w-auto overflow-hidden bg-brand-red text-white px-10 py-5 rounded-2xl font-bold transition-all hover:scale-[1.02] active:scale-95 shadow-2xl shadow-brand-red/30">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <?php echo $hero['cta_primary']; ?>
                                <i class="ph ph-arrow-right font-bold transition-transform group-hover:translate-x-1"></i>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        </a>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-14 pt-8 border-t border-brand-orange/20 flex flex-wrap justify-center lg:justify-start gap-8 opacity-70">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-brand-green text-xl"></i>
                            <span class="text-sm font-medium">Bahan Organik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-brand-green text-xl"></i>
                            <span class="text-sm font-medium">Koki Berlisensi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-brand-green text-xl"></i>
                            <span class="text-sm font-medium">Pengiriman Cepat</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Plate (Right) -->
                <div class="w-full lg:w-[45%] relative flex justify-center items-center">
                    <!-- Glow Behind Plate -->
                    <div class="absolute inset-0 bg-brand-orange/20 rounded-full blur-[120px] scale-75 opacity-50"></div>
                    
                    <div class="relative w-full max-w-[550px] aspect-square z-10 p-4">
                        <div class="w-full h-full rounded-[5rem] border-[12px] border-white shadow-2xl overflow-hidden animate-float-slow transition-transform hover:scale-105 duration-700">
                             <img src="<?php echo !empty($hero['main_image']) ? $hero['main_image'] : 'assets/img/hero_hd.png'; ?>" alt="Healthy Food Bowl HD" 
                                  class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Floating Engagement Card 1: Customer Rating -->
                        <div class="absolute -top-4 -right-4 lg:-right-8 animate-float delay-75 hero-glass-card p-5 z-20 flex items-center gap-4 transition-transform hover:scale-105">
                            <div class="flex -space-x-2">
                                <div class="w-10 h-10 rounded-lg border-2 border-white bg-slate-200"></div>
                                <div class="w-10 h-10 rounded-lg border-2 border-white bg-slate-300"></div>
                                <div class="w-10 h-10 rounded-lg border-2 border-white bg-slate-400"></div>
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-yellow-500 mb-0.5">
                                    <i class="ph-fill ph-star"></i>
                                    <i class="ph-fill ph-star"></i>
                                    <i class="ph-fill ph-star"></i>
                                    <i class="ph-fill ph-star"></i>
                                    <i class="ph-fill ph-star"></i>
                                </div>
                                <p class="text-[10px] font-bold text-brand-dark uppercase tracking-wider">10K+ Pelanggan Puas</p>
                            </div>
                        </div>

                        <!-- Floating Engagement Card 2: Calories/Nutrients -->
                        <div class="absolute -bottom-8 -left-4 lg:-left-12 animate-float-reverse hero-glass-card p-5 z-20 flex items-center gap-4 transition-transform hover:scale-105">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-brand-orange shadow-inner">
                                <i class="ph-fill ph-leaf text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-brand-dark font-black text-xl leading-none">100%</p>
                                <p class="text-[10px] text-brand-gray font-bold uppercase tracking-wider mt-1">Bahan Alami</p>
                            </div>
                        </div>

                        <!-- Abstract Shapes -->
                        <div class="absolute top-[20%] right-[-10%] w-24 h-24 bg-brand-red/5 rounded-full blur-xl -z-10 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Menu Section -->
    <section id="menu" class="py-20 relative z-10">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-brand-dark mb-3">Menu Unggulan Kami</h2>
                <div class="flex items-center justify-center gap-3">
                    <div class="h-0.5 w-16 bg-brand-red rounded-full"></div>
                    <i class="ph-fill ph-fork-knife text-brand-red text-lg"></i>
                    <div class="h-0.5 w-16 bg-brand-red rounded-full"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 pt-10">
                <?php foreach($menu_items as $item): ?>
                <div class="relative pt-20 group">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-40 h-40 z-10 transition-transform duration-300 group-hover:-translate-y-4">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover rounded-[2.5rem] border-[6px] border-[#FEF8F0] shadow-xl text-center flex items-center justify-center text-xs">
                    </div>
                    <div class="card-<?php echo $item['category_color']; ?> rounded-[2.5rem] p-6 pt-24 pb-8 text-center text-white relative overflow-hidden shadow-lg shadow-<?php echo $item['category_color']; ?>-500/30">
                        <h3 class="font-bold text-xl mb-1"><?php echo $item['name']; ?></h3>
                        <p class="text-<?php echo $item['category_color']; ?>-100 text-sm mb-4"><?php echo $item['description']; ?></p>
                        <div class="flex justify-between items-center bg-white/20 rounded-full p-1 pl-4 backdrop-blur-sm">
                            <span class="font-bold text-lg">Rp <?php echo $item['price']; ?></span>
                            <button class="bg-white text-brand-dark px-4 py-2 rounded-full text-xs font-bold hover:bg-brand-dark hover:text-white transition-colors flex items-center gap-1">
                                Pesan <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                        <div class="absolute top-4 right-4 flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-1 rounded-full backdrop-blur-sm">
                            <i class="ph-fill ph-star text-yellow-300"></i> <?php echo number_format($item['rating'], 1); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="about" class="py-20 bg-white rounded-[3rem] shadow-sm mx-4 lg:mx-8 mb-20 relative z-0">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                <div class="w-full lg:w-1/2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-1 sm:col-span-1 sm:row-span-2 relative rounded-3xl overflow-hidden shadow-lg bg-[#FFD166] group h-64 sm:h-auto text-center flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10"></div>
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 text-center w-full">
                            <h3 class="text-white font-serif font-bold text-2xl drop-shadow-md">TASTY BURGER</h3>
                            <span class="bg-brand-red text-white text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block shadow-md">BARU!</span>
                        </div>
                        <img src="assets/img/bento_1.png" alt="Burger" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    
                    <div class="col-span-1 relative rounded-3xl overflow-hidden shadow-lg group h-48 sm:h-56">
                        <img src="assets/img/bento_2.png" alt="Pancake" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>

                    <div class="col-span-1 relative rounded-3xl overflow-hidden shadow-lg group h-48 sm:h-56">
                         <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 flex items-end p-4">
                            <button class="bg-white/30 backdrop-blur-md text-white font-medium px-4 py-2 rounded-full w-full hover:bg-white hover:text-brand-dark transition-colors border border-white/50 text-sm uppercase">Pesan Sekarang</button>
                         </div>
                        <img src="assets/img/bento_3.png" alt="Meatballs" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>

                <div class="w-full lg:w-1/2">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-10 text-brand-dark">Kenapa Memilih Restoran Kami?</h2>
                    <div class="space-y-8">
                        <?php foreach($features as $feature): ?>
                        <div class="flex items-start gap-5 group cursor-pointer">
                            <div class="w-14 h-14 rounded-full <?php echo $feature['bg_color']; ?> flex items-center justify-center shrink-0 group-hover:bg-brand-orange transition-colors">
                                <i class="ph-fill <?php echo $feature['icon']; ?> text-3xl <?php echo $feature['text_color']; ?> group-hover:text-white transition-colors"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl mb-1 group-hover:text-brand-orange transition-colors"><?php echo $feature['title']; ?></h3>
                                <p class="text-brand-gray text-sm"><?php echo $feature['description']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section id="testimonials" class="py-20 relative z-10">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-brand-dark mb-4">Apa Kata Pelanggan Kami</h2>
                <p class="text-brand-gray max-w-2xl mx-auto">Kami selalu berusaha memberikan layanan terbaik. Berikut adalah ulasan nyata dari mereka.</p>
            </div>
            
            <div id="testimonials-grid" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($testimonials as $t): ?>
                <div class="testimonial-card bg-white p-8 rounded-3xl shadow-sm border border-orange-50 hover:-translate-y-2 transition-transform duration-300 relative">
                    <i class="ph-fill ph-quotes text-5xl text-orange-100 absolute top-6 right-6"></i>
                    <div class="flex gap-1 text-yellow-400 mb-6 text-xl">
                        <?php for($i=1; $i<=5; $i++) echo ($i <= $t['rating'] ? '<i class="ph-fill ph-star"></i>' : '<i class="ph ph-star"></i>'); ?>
                    </div>
                    <p class="text-brand-gray mb-8 leading-relaxed">"<?php echo $t['comment']; ?>"</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xl shrink-0">
                            <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark"><?php echo $t['name']; ?></h4>
                            <p class="text-[10px] font-bold text-brand-red uppercase tracking-widest mt-1 opacity-70">
                                <i class="ph-bold ph-clock"></i> <?php echo date('H:i, d M Y', strtotime($t['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Simplified Review Form -->
            <div class="mt-20 max-w-2xl mx-auto">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 pt-10 pb-2 text-center">
                        <h3 class="font-serif text-3xl font-bold text-brand-dark mb-2">Tulis Ulasan Anda</h3>
                        <p class="text-brand-gray text-sm">Bagikan pengalaman Anda bersama kami</p>
                    </div>

                    <div class="px-8 py-10">
                        <form id="review-form" class="space-y-6">
                                <div class="review-field-group col-span-2">
                                    <label class="block text-sm font-medium text-brand-dark mb-2">Nama Lengkap</label>
                                    <input id="review-name" type="text" placeholder="Masukkan nama Anda" required 
                                        class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none text-brand-dark transition-all placeholder-gray-400">
                                </div>

                            <div class="review-field-group text-center sm:text-left">
                                <label class="block text-sm font-medium text-brand-dark mb-3">Rating Anda</label>
                                <div class="flex items-center justify-center sm:justify-start gap-1" id="star-rating-container">
                                    <i class="ph-fill ph-star text-gray-200 text-4xl cursor-pointer star-btn transition-all" data-value="1"></i>
                                    <i class="ph-fill ph-star text-gray-200 text-4xl cursor-pointer star-btn transition-all" data-value="2"></i>
                                    <i class="ph-fill ph-star text-gray-200 text-4xl cursor-pointer star-btn transition-all" data-value="3"></i>
                                    <i class="ph-fill ph-star text-gray-200 text-4xl cursor-pointer star-btn transition-all" data-value="4"></i>
                                    <i class="ph-fill ph-star text-gray-200 text-4xl cursor-pointer star-btn transition-all" data-value="5"></i>
                                    <span id="rating-label" class="ml-3 text-xs text-brand-gray font-medium uppercase tracking-wider"></span>
                                </div>
                            </div>

                            <div class="review-field-group">
                                <label class="block text-sm font-medium text-brand-dark mb-2">Pesan Ulasan</label>
                                <textarea id="review-comment" placeholder="Tuliskan pengalaman Anda..." rows="4" required 
                                    class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none text-brand-dark transition-all placeholder-gray-400 resize-none"></textarea>
                            </div>

                            <div id="review-alert" class="hidden"></div>

                            <button id="submit-review-btn" type="button" 
                                class="w-full bg-brand-red text-white font-bold px-8 py-4 rounded-xl hover:bg-rose-600 transition-all shadow-md active:scale-[0.98]">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 mb-10 relative z-10">
        <div class="container mx-auto px-6">
            <div class="bg-[#FFF4E6] rounded-[3rem] p-8 sm:p-10 lg:p-16 flex flex-col lg:flex-row gap-12 lg:gap-20 shadow-sm border border-orange-50 overflow-hidden relative">
                <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full border-[40px] border-white/40 pointer-events-none"></div>

                <div class="w-full lg:w-3/5 relative z-10">
                    <h2 class="font-serif text-3xl lg:text-4xl font-bold text-brand-dark mb-4">Hubungi Kami</h2>
                    <p class="text-brand-gray mb-8">Punya pertanyaan, kritik, saran, atau ingin melakukan reservasi meja? Kami akan segera membalas Anda.</p>
                    
                    <form class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-brand-dark mb-1">Nama Lengkap</label>
                                <input type="text" placeholder="Masukkan nama" class="w-full px-5 py-3.5 rounded-xl border-none shadow-sm focus:ring-2 focus:ring-brand-red outline-none text-brand-dark bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-dark mb-1">Nomor Telepon</label>
                                <input type="tel" placeholder="0812 xxx xxx" class="w-full px-5 py-3.5 rounded-xl border-none shadow-sm focus:ring-2 focus:ring-brand-red outline-none text-brand-dark bg-white">
                            </div>
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-brand-dark mb-1">Pesan Anda</label>
                             <textarea placeholder="Tuliskan pesan atau detail reservasi Anda..." rows="4" class="w-full px-5 py-3.5 rounded-xl border-none shadow-sm focus:ring-2 focus:ring-brand-red outline-none text-brand-dark bg-white resize-none"></textarea>
                        </div>
                        <button type="button" class="bg-brand-red text-white font-bold px-8 py-4 rounded-xl hover:bg-rose-600 transition-colors w-full sm:w-auto shadow-lg shadow-brand-red/20 mt-2">
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>

                <div class="w-full lg:w-2/5 relative z-10 flex flex-col justify-center">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-orange-50 space-y-8">
                        <div>
                            <h3 class="font-serif font-bold text-2xl text-brand-dark mb-6">Informasi Kontak</h3>
                        </div>
                        
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0 text-brand-red"><i class="ph-fill ph-map-pin text-2xl"></i></div>
                            <div>
                                <h4 class="font-bold text-brand-dark mb-1">Alamat Restoran</h4>
                                <p class="text-brand-gray text-sm leading-relaxed"><?php echo $settings['address']; ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center shrink-0 text-brand-orange"><i class="ph-fill ph-phone text-2xl"></i></div>
                            <div>
                                <h4 class="font-bold text-brand-dark mb-1">Telepon & WA</h4>
                                <p class="text-brand-gray text-sm">+<?php echo $settings['wa_number']; ?></p>
                                <p class="text-brand-gray text-sm"><?php echo $settings['phone']; ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0 text-brand-green"><i class="ph-fill ph-clock text-2xl"></i></div>
                            <div>
                                <h4 class="font-bold text-brand-dark mb-1">Jam Operasional</h4>
                                <p class="text-brand-gray text-sm"><?php echo $settings['opening_hours_week']; ?></p>
                                <p class="text-brand-gray text-sm"><?php echo $settings['opening_hours_weekend']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="pb-20 relative z-10">
        <div class="container mx-auto px-6">
            <div class="w-full h-[450px] rounded-[3rem] overflow-hidden shadow-lg border-8 border-white">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3257.497728749219!2d108.3596774740006!3d-7.186140892818977!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f43087e24f9d3%3A0x34cfd3589f2615d0!2sSMK%20Negeri%201%20Kawali!5e1!3m2!1sid!2sid!4v1775051857378!5m2!1sid!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-dark text-white pt-20 pb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-16">
                
                <div class="lg:col-span-1">
                    <a href="#" class="flex items-center gap-2 mb-6">
                        <div class="bg-white text-brand-red p-1.5 rounded-full">
                            <i class="ph-fill ph-hamburger text-xl"></i>
                        </div>
                        <span class="font-bold text-2xl tracking-tight"><?php echo $settings['logo_text']; ?></span>
                    </a>
                    <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                        <?php echo $settings['logo_text']; ?> adalah restoran modern yang fokus pada penyajian makanan lezat, bergizi, dan higienis.
                    </p>
                </div>

                <div>
                    <h4 class="font-serif font-bold text-xl mb-6 border-b border-white/20 pb-2 inline-block">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin text-brand-red text-xl shrink-0 mt-0.5"></i>
                            <span><?php echo $settings['address']; ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-envelope-simple text-brand-red text-xl shrink-0"></i>
                            <span><?php echo $settings['email']; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $settings['logo_text']; ?> Restaurant. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
