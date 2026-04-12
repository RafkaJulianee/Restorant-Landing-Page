<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost' || php_sapi_name() == 'cli') {
    // 🏠 LOCAL (XAMPP)
    $host = 'localhost';
    $db   = 'restolandingpage';
    $user = 'root';
    $pass = '';
} else {
    // 🌍 HOSTING (InfinityFree)
    $host = 'sql203.infinityfree.com';
    $db   = 'if0_41564029_restolandingpage';
    $user = 'if0_41564029';
    $pass = 'gCdDTgauFyD9';
}

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        // Detect current depth for redirection
        $current_path = $_SERVER['PHP_SELF'];
        $redirect = "login.php";
        if (strpos($current_path, '/modules/') !== false) {
            $redirect = "../login.php";
        }
        header("Location: $redirect");
        exit;
    }
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}