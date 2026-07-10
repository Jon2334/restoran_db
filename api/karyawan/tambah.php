<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_karyawan = trim($_POST['kode_karyawan']);
    $nama_karyawan = trim($_POST['nama_karyawan']);
    $jabatan = trim($_POST['jabatan']);

    if (empty($kode_karyawan) || empty($nama_karyawan) || empty($jabatan)) {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt_check = $pdo->prepare("SELECT kode_karyawan FROM karyawan WHERE kode_karyawan = ?");
        $stmt_check->execute([$kode_karyawan]);
        if ($stmt_check->rowCount() > 0) {
            $error = "Kode Karyawan sudah ada!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO karyawan (kode_karyawan, nama_karyawan, jabatan) VALUES (?, ?, ?)");
            if ($stmt->execute([$kode_karyawan, $nama_karyawan, $jabatan])) {
                echo "<script>alert('Data karyawan berhasil ditambahkan!'); window.location.href='index.php';</script>";
                exit();
            } else {
                $error = "Gagal menyimpan data!";
            }
        }
    }
}

$stmt_last = $pdo->query("SELECT kode_karyawan FROM karyawan ORDER BY kode_karyawan DESC LIMIT 1");
$last_kode = $stmt_last->fetchColumn();
$new_kode = 'K001';
if ($last_kode) {
    $num = (int) substr($last_kode, 1);
    $new_kode = 'K' . sprintf('%03d', $num + 1);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Karyawan</h1>
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST">
            <div class="mb-3 row">
                <label for="kode_karyawan" class="col-sm-2 col-form-label">Kode Karyawan</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= htmlspecialchars($new_kode) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_karyawan" class="col-sm-2 col-form-label">Nama Karyawan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                    <button type="reset" class="btn btn-warning"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>