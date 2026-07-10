<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT * FROM karyawan ORDER BY kode_karyawan DESC");
$karyawan = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Karyawan</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th width="25%">Jabatan</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($karyawan) > 0): ?>
                        <?php $no = 1; foreach ($karyawan as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_karyawan']) ?></span></td>
                            <td><?= htmlspecialchars($row['nama_karyawan']) ?></td>
                            <td><?= htmlspecialchars($row['jabatan']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['kode_karyawan'] ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="hapus.php?id=<?= $row['kode_karyawan'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data karyawan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>