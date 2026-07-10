<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_faktur = $_POST['no_faktur'];
    $tanggal = $_POST['tanggal'];
    $kode_pel = $_POST['kode_pel'];
    $total = $_POST['total'];
    $items = isset($_POST['items']) ? $_POST['items'] : [];

    if (empty($no_faktur) || empty($kode_pel) || empty($items)) {
        echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
        exit();
    }

    try {
        // Mulai Transaction
        $pdo->beginTransaction();

        // 1. Insert ke tabel transaksi (Header)
        $stmt_trx = $pdo->prepare("INSERT INTO transaksi (no_faktur, tanggal, kode_pel, total) VALUES (?, ?, ?, ?)");
        $stmt_trx->execute([$no_faktur, $tanggal, $kode_pel, $total]);

        // 2. Insert ke tabel detail_transaksi
        $stmt_detail = $pdo->prepare("INSERT INTO detail_transaksi (no_faktur, kode_menu, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($items as $item) {
            $stmt_detail->execute([
                $no_faktur,
                $item['kode_menu'],
                $item['harga'],
                $item['jumlah'],
                $item['subtotal']
            ]);
        }

        // Commit Transaction jika semua berhasil
        $pdo->commit();

        echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.location.href='cetak.php?id=" . $no_faktur . "';
        </script>";

    } catch (PDOException $e) {
        // Rollback jika terjadi error
        $pdo->rollBack();
        echo "<script>alert('Gagal menyimpan transaksi: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
}
?>