<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$kode_pel_lama = $_GET['id'];
$error = '';

$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE kode_pel = ?");
$stmt->execute([$kode_pel_lama]);
$pel = $stmt->fetch();

if (!$pel) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_pel = trim($_POST['kode_pel']);
    $nama_pel = trim($_POST['nama_pel']);
    $alamat = trim($_POST['alamat']);

    if (empty($kode_pel) || empty($nama_pel)) {
        $error = "Kode dan Nama wajib diisi!";
    } else {
        if ($kode_pel != $kode_pel_lama) {
            $stmt_check = $pdo->prepare("SELECT kode_pel FROM pelanggan WHERE kode_pel = ?");
            $stmt_check->execute([$kode_pel]);
            if ($stmt_check->rowCount() > 0) {
                $error = "Kode Pelanggan sudah ada!";
            }
        }

        if (empty($error)) {
            try {
                $stmt_upd = $pdo->prepare("UPDATE pelanggan SET kode_pel = ?, nama_pel = ?, alamat = ? WHERE kode_pel = ?");
                if ($stmt_upd->execute([$kode_pel, $nama_pel, $alamat, $kode_pel_lama])) {
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
    <h1 class="h3 mb-0 text-gray-800">Edit Pelanggan</h1>
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
                    <input type="text" class="form-control" id="kode_pel" name="kode_pel" value="<?= htmlspecialchars($pel['kode_pel']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_pel" class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_pel" name="nama_pel" value="<?= htmlspecialchars($pel['nama_pel']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-6">
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($pel['alamat']) ?></textarea>
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