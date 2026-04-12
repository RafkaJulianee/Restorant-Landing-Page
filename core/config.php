<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
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