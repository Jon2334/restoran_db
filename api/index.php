<?php
session_start();

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Redirect ke dashboard (halaman utama)
header("Location: dashboard.php");
exit();
?>