<?php
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['id'])) {
    $kode_menu = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM menu WHERE kode_menu = ?");
        if ($stmt->execute([$kode_menu])) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        // Jika terjadi error foreign key constraint
        if ($e->getCode() == '23000') {
            echo "<script>alert('Gagal menghapus! Data menu ini sedang digunakan dalam transaksi.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
        }
    }
} else {
    header("Location: index.php");
}
?>