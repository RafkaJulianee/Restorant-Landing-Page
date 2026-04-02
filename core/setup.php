<?php
require_once 'config.php';

echo "<h2>🔧 Foody CMS - Database Auto Setup</h2>";

try {
    // Connect to server without database first to create it
    $pdo_temp = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    
    // Read the SQL file
    $sql = file_get_contents('database.sql');
    
    // Split by semicolon and execute each
    $queries = explode(';', $sql);
    
    $success = 0;
    $errors = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        try {
            $pdo_temp->exec($query);
            $success++;
        } catch (PDOException $e) {
            // Ignore if database/table already exists
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
                $errors++;
            }
        }
    }

    
    if ($errors == 0) {
        echo "<p style='color:green; font-weight:bold;'>✅ Setup Berhasil! Semua tabel telah dibuat.</p>";
        echo "<p>Silakan hapus file <code>setup.php</code> ini demi keamanan.</p>";
        echo "<p><a href='admin/login.php' style='padding:10px 20px; background:#F43F5E; color:white; text-decoration:none; border-radius:10px; display:inline-block; margin-top:20px;'>Ke Halaman Login Admin</a></p>";
    } else {
        echo "<p style='color:orange;'>⚠️ Setup selesai dengan beberapa peringatan. Cek pesan error di atas.</p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red;'>💥 Gagal total: " . $e->getMessage() . "</p>";
}
?>
