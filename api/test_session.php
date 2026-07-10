<?php
// Let's check session behavior
$code = <<<'PHP'
<?php
// Only start session if one doesn't exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}
PHP;
