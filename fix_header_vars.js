const fs = require('fs');

function fixHeaderUndefinedVars(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Dalam migrasi Cookie, kita lupa kalau $_SESSION['nama'] dan $_SESSION['level'] bisa undefined di Vercel jika belum direpopulasi dari Cookie.
        // Kita perbaiki agar menggunakan fallbacks dari $_COOKIE jika $_SESSION kosong.

        let fixCode = `<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Repopulate session dari cookie jika session kosong di serverless Vercel
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'] ?? '';
    $_SESSION['nama'] = $_COOKIE['nama'] ?? '';
    $_SESSION['level'] = $_COOKIE['level'] ?? '';
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

// Variabel default untuk header UI agar tidak error warning/deprecated htmlspecialchars(null)
$user_nama = isset($_SESSION['nama']) && $_SESSION['nama'] !== '' ? $_SESSION['nama'] : 'Pengguna';
$user_level = isset($_SESSION['level']) && $_SESSION['level'] !== '' ? $_SESSION['level'] : 'User';
?>`;

        // Replace everything from the start of the file to the closing PHP tag
        content = content.replace(/^<\?php[\s\S]*?\?>/m, fixCode);
        
        // Replace htmlspecialchars($_SESSION['nama']) with htmlspecialchars($user_nama)
        content = content.replace(/htmlspecialchars\(\$_SESSION\['nama'\]\)/g, "htmlspecialchars($user_nama)");
        content = content.replace(/htmlspecialchars\(\$_SESSION\['level'\]\)/g, "htmlspecialchars($user_level)");
        content = content.replace(/urlencode\(\$_SESSION\['nama'\]\)/g, "urlencode($user_nama)");
        
        // Remove those specific warning lines that leaked into the HTML output directly if they exist
        content = content.replace(/<\?= htmlspecialchars\(\$_SESSION\['nama'\]\) \?>/g, "<?= htmlspecialchars($user_nama) ?>");
        content = content.replace(/<\?= htmlspecialchars\(\$_SESSION\['level'\]\) \?>/g, "<?= htmlspecialchars($user_level) ?>");
        content = content.replace(/<\?= urlencode\(\$_SESSION\['nama'\]\) \?>/g, "<?= urlencode($user_nama) ?>");

        fs.writeFileSync(filePath, content);
        console.log(`Fixed variables in ${filePath}`);
    }
}

fixHeaderUndefinedVars('api/includes/header.php');
fixHeaderUndefinedVars('includes/header.php');
