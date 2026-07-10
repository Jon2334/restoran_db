<?php
// Mengambil konfigurasi dari Environment Variables Vercel, 
// atau fallback ke localhost jika dijalankan lokal
$db_url = getenv('DATABASE_URL');

try {
    if ($db_url) {
        // Jika ada DATABASE_URL (seperti format dari Neon DB)
        $db = parse_url($db_url);
        
        $host = $db["host"];
        $port = isset($db["port"]) ? $db["port"] : 5432;
        $username = $db["user"];
        $password = $db["pass"];
        $dbname = ltrim($db["path"], "/");
        
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $username, $password);
    } else {
        // Fallback konfigurasi lokal (MySQL)
        $host = 'localhost';
        $dbname = 'db_restoran';
        $username = 'root';
        $password = '';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    }
    
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
