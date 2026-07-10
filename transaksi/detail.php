<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$no_faktur = $_GET['id'];

// Ambil data header transaksi
$stmt_header = $pdo->prepare("SELECT t.*, p.nama_pel, p.alamat FROM transaksi t JOIN pelanggan p ON t.kode_pel = p.kode_pel WHERE t.no_faktur = ?");
$stmt_header->execute([$no_faktur]);
$trx = $stmt_header->fetch();

if (!$trx) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

// Ambil data detail transaksi
$stmt_detail = $pdo->prepare("SELECT d.*, m.nama_menu FROM detail_transaksi d JOIN menu m ON d.kode_menu = m.kode_menu WHERE d.no_faktur = ?");
$stmt_detail->execute([$no_faktur]);
$details = $stmt_detail->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
    <div>
        <a href="cetak.php?id=<?= htmlspecialchars($no_faktur) ?>" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Faktur</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="40%" class="text-muted">No Faktur</td>
                        <td width="5%">:</td>
                        <td class="fw-bold"><?= htmlspecialchars($trx['no_faktur']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>:</td>
                        <td><?= date('d F Y', strtotime($trx['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Pelanggan</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($trx['nama_pel']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($trx['alamat']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Menu</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="ps-4">No</th>
                                <th>Nama Menu</th>
                                <th width="20%" class="text-end">Harga</th>
                                <th width="10%" class="text-center">Jumlah</th>
                                <th width="25%" class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($details as $d): ?>
                            <tr>
                                <td class="ps-4"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($d['nama_menu']) ?></td>
                                <td class="text-end">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= htmlspecialchars($d['jumlah']) ?></td>
                                <td class="text-end fw-bold pe-4">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end fw-bold fs-5">TOTAL AKHIR:</td>
                                <td class="text-end fw-bold fs-5 text-primary pe-4">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>