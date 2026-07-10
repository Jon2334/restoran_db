<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_pel = trim($_POST['kode_pel']);
    $nama_pel = trim($_POST['nama_pel']);
    $alamat = trim($_POST['alamat']);

    if (empty($kode_pel) || empty($nama_pel)) {
        $error = "Kode Pelanggan dan Nama Pelanggan wajib diisi!";
    } else {
        $stmt_check = $pdo->prepare("SELECT kode_pel FROM pelanggan WHERE kode_pel = ?");
        $stmt_check->execute([$kode_pel]);
        if ($stmt_check->rowCount() > 0) {
            $error = "Kode Pelanggan sudah ada!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO pelanggan (kode_pel, nama_pel, alamat) VALUES (?, ?, ?)");
            if ($stmt->execute([$kode_pel, $nama_pel, $alamat])) {
                echo "<script>alert('Data pelanggan berhasil ditambahkan!'); window.location.href='index.php';</script>";
                exit();
            } else {
                $error = "Gagal menyimpan data!";
            }
        }
    }
}

$stmt_last = $pdo->query("SELECT kode_pel FROM pelanggan ORDER BY kode_pel DESC LIMIT 1");
$last_kode = $stmt_last->fetchColumn();
$new_kode = 'P001';
if ($last_kode) {
    $num = (int) substr($last_kode, 1);
    $new_kode = 'P' . sprintf('%03d', $num + 1);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Pelanggan</h1>
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
                <label for="kode_pel" class="col-sm-2 col-form-label">Kode Pelanggan</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_pel" name="kode_pel" value="<?= htmlspecialchars($new_kode) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_pel" class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_pel" name="nama_pel" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-6">
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
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