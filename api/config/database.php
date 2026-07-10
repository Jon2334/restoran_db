<?php
$db_url = getenv('DATABASE_URL') ?: 'postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require';

try {
    if ($db_url && strpos($db_url, 'postgres') !== false) {
        $db = parse_url($db_url);
        
        $host = $db["host"];
        $port = isset($db["port"]) ? $db["port"] : 5432;
        $username = $db["user"];
        $password = $db["pass"];
        $dbname = ltrim($db["path"], "/");
        
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 3 // Don't hang forever
        ];
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $username, $password, $options);
    } else {
        $host = 'localhost';
        $dbname = 'db_restoran';
        $username = 'root';
        $password = '';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    }
    
    
    
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>