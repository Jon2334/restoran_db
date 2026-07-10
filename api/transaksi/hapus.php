<?php
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['id'])) {
    session_start();
    if ($_SESSION['level'] != 'Admin') {
        echo "<script>alert('Anda tidak memiliki akses untuk menghapus transaksi!'); window.location.href='index.php';</script>";
        exit();
    }

    $no_faktur = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM transaksi WHERE no_faktur = ?");
        // Karena ada ON DELETE CASCADE di detail_transaksi, detailnya akan ikut terhapus otomatis.
        if ($stmt->execute([$no_faktur])) {
            echo "<script>alert('Data transaksi dan detailnya berhasil dihapus!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>