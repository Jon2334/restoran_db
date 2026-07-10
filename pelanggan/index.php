<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT * FROM pelanggan ORDER BY kode_pel DESC");
$pelanggan = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pelanggan</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pelanggan</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Pelanggan</th>
                        <th width="25%">Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pelanggan) > 0): ?>
                        <?php $no = 1; foreach ($pelanggan as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_pel']) ?></span></td>
                            <td><?= htmlspecialchars($row['nama_pel']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['kode_pel'] ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="hapus.php?id=<?= $row['kode_pel'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pelanggan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>