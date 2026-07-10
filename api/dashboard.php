<?php
session_start();

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// ... (sisa kode dashboard.php bisa menggunakan absolute path Vercel)
