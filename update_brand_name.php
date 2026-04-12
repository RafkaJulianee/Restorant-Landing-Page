<?php
require_once 'core/config.php';

try {
    $stmt = $pdo->prepare("UPDATE settings SET logo_text = ? WHERE id = 1");
    if ($stmt->execute(['MyCode'])) {
        echo "Site title successfully updated to 'MyCode'!\n";
    } else {
        echo "Failed to update site title.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
