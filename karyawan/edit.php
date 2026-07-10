<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$kode_karyawan_lama = $_GET['id'];
$error = '';

$stmt = $pdo->prepare("SELECT * FROM karyawan WHERE kode_karyawan = ?");
$stmt->execute([$kode_karyawan_lama]);
$kar = $stmt->fetch();

if (!$kar) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_karyawan = trim($_POST['kode_karyawan']);
    $nama_karyawan = trim($_POST['nama_karyawan']);
    $jabatan = trim($_POST['jabatan']);

    if (empty($kode_karyawan) || empty($nama_karyawan) || empty($jabatan)) {
        $error = "Semua field wajib diisi!";
    } else {
        if ($kode_karyawan != $kode_karyawan_lama) {
            $stmt_check = $pdo->prepare("SELECT kode_karyawan FROM karyawan WHERE kode_karyawan = ?");
            $stmt_check->execute([$kode_karyawan]);
            if ($stmt_check->rowCount() > 0) {
                $error = "Kode Karyawan sudah ada!";
            }
        }

        if (empty($error)) {
            try {
                $stmt_upd = $pdo->prepare("UPDATE karyawan SET kode_karyawan = ?, nama_karyawan = ?, jabatan = ? WHERE kode_karyawan = ?");
                if ($stmt_upd->execute([$kode_karyawan, $nama_karyawan, $jabatan, $kode_karyawan_lama])) {
                    echo "<script>alert('Data berhasil diupdate!'); window.location.href='index.php';</script>";
                    exit();
                } else {
                    $error = "Gagal mengupdate data!";
                }
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Karyawan</h1>
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
                    <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= htmlspecialchars($kar['kode_karyawan']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_karyawan" class="col-sm-2 col-form-label">Nama Karyawan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?= htmlspecialchars($kar['nama_karyawan']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= htmlspecialchars($kar['jabatan']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>