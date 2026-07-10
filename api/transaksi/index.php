<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT t.*, p.nama_pel FROM transaksi t JOIN pelanggan p ON t.kode_pel = p.kode_pel ORDER BY t.tanggal DESC, t.no_faktur DESC");
$transaksi = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Input Transaksi Baru</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">No Faktur</th>
                        <th width="15%">Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th width="15%">Total</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transaksi) > 0): ?>
                        <?php $no = 1; foreach ($transaksi as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['no_faktur']) ?></span></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($row['nama_pel']) ?></td>
                            <td class="fw-bold">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $row['no_faktur'] ?>" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i> Detail</a>
                                <a href="cetak.php?id=<?= $row['no_faktur'] ?>" target="_blank" class="btn btn-sm btn-success" title="Cetak"><i class="bi bi-printer"></i></a>
                                <?php if ($_SESSION['level'] == 'Admin'): ?>
                                <a href="hapus.php?id=<?= $row['no_faktur'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini? Seluruh detailnya juga akan terhapus.')"><i class="bi bi-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>