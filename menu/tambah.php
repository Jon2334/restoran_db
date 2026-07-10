<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_menu = trim($_POST['kode_menu']);
    $nama_menu = trim($_POST['nama_menu']);
    $jenis = $_POST['jenis'];
    $harga = trim($_POST['harga']);

    // Validasi
    if (empty($kode_menu) || empty($nama_menu) || empty($jenis) || empty($harga)) {
        $error = "Semua field wajib diisi!";
    } elseif (!is_numeric($harga)) {
        $error = "Harga harus berupa angka!";
    } else {
        // Cek apakah kode_menu sudah ada
        $stmt_check = $pdo->prepare("SELECT kode_menu FROM menu WHERE kode_menu = ?");
        $stmt_check->execute([$kode_menu]);
        if ($stmt_check->rowCount() > 0) {
            $error = "Kode Menu sudah ada!";
        } else {
            // Insert data
            $stmt = $pdo->prepare("INSERT INTO menu (kode_menu, nama_menu, jenis, harga) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$kode_menu, $nama_menu, $jenis, $harga])) {
                echo "<script>alert('Data menu berhasil ditambahkan!'); window.location.href='index.php';</script>";
                exit();
            } else {
                $error = "Gagal menyimpan data!";
            }
        }
    }
}

// Generate Kode Menu Otomatis (opsional, tapi bagus untuk UX)
$stmt_last = $pdo->query("SELECT kode_menu FROM menu ORDER BY kode_menu DESC LIMIT 1");
$last_kode = $stmt_last->fetchColumn();
$new_kode = 'M001';
if ($last_kode) {
    $num = (int) substr($last_kode, 1);
    $new_kode = 'M' . sprintf('%03d', $num + 1);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Menu</h1>
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
                <label for="kode_menu" class="col-sm-2 col-form-label">Kode Menu</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_menu" name="kode_menu" value="<?= htmlspecialchars($new_kode) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_menu" class="col-sm-2 col-form-label">Nama Menu</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="jenis" class="col-sm-2 col-form-label">Jenis</label>
                <div class="col-sm-4">
                    <select class="form-select" id="jenis" name="jenis" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Snack">Snack</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" required>
                    </div>
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