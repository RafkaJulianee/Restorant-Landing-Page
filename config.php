<?php
// Configuration for Database Connection

$host = 'localhost';
$db   = 'restolandingpage';
$user = 'root'; // default laragon
$pass = '';     // default laragon
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
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// System-wide constants
define('SITE_URL', 'http://localhost/Restorant%20Landing%20Page/');
define('UPLOAD_DIR', '../assets/img/');

// Authentication check function
function check_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>
