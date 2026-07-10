<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT * FROM menu ORDER BY kode_menu DESC");
$menus = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Menu</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Menu</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Menu</th>
                        <th>Nama Menu</th>
                        <th width="15%">Jenis</th>
                        <th width="15%">Harga</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($menus) > 0): ?>
                        <?php $no = 1; foreach ($menus as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_menu']) ?></span></td>
                            <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                            <td>
                                <?php if($row['jenis'] == 'Makanan'): ?>
                                    <span class="badge bg-success">Makanan</span>
                                <?php elseif($row['jenis'] == 'Minuman'): ?>
                                    <span class="badge bg-info">Minuman</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Snack</span>
                                <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['kode_menu'] ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="hapus.php?id=<?= $row['kode_menu'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data menu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>