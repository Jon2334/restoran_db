<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$kode_menu_lama = $_GET['id'];
$error = '';

// Ambil data menu
$stmt = $pdo->prepare("SELECT * FROM menu WHERE kode_menu = ?");
$stmt->execute([$kode_menu_lama]);
$menu = $stmt->fetch();

if (!$menu) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

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
        // Jika kode menu diubah, cek apakah kode baru sudah ada
        if ($kode_menu != $kode_menu_lama) {
            $stmt_check = $pdo->prepare("SELECT kode_menu FROM menu WHERE kode_menu = ?");
            $stmt_check->execute([$kode_menu]);
            if ($stmt_check->rowCount() > 0) {
                $error = "Kode Menu sudah ada!";
            }
        }

        if (empty($error)) {
            // Update data
            try {
                // Gunakan UPDATE ... WHERE kode_menu = kode_menu_lama
                // Karena kita menggunakan ON UPDATE CASCADE di tabel relasi, ini akan aman jika kode_menu berubah
                $stmt_upd = $pdo->prepare("UPDATE menu SET kode_menu = ?, nama_menu = ?, jenis = ?, harga = ? WHERE kode_menu = ?");
                if ($stmt_upd->execute([$kode_menu, $nama_menu, $jenis, $harga, $kode_menu_lama])) {
                    echo "<script>alert('Data menu berhasil diupdate!'); window.location.href='index.php';</script>";
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
    <h1 class="h3 mb-0 text-gray-800">Edit Menu</h1>
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
                    <!-- Readonly karena merupakan primary key (opsional bisa diubah tapi harus ditangani cascadenya, di sini kita izinkan diubah dengan penanganan di atas) -->
                    <input type="text" class="form-control" id="kode_menu" name="kode_menu" value="<?= htmlspecialchars($menu['kode_menu']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama_menu" class="col-sm-2 col-form-label">Nama Menu</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_menu" name="nama_menu" value="<?= htmlspecialchars($menu['nama_menu']) ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="jenis" class="col-sm-2 col-form-label">Jenis</label>
                <div class="col-sm-4">
                    <select class="form-select" id="jenis" name="jenis" required>
                        <option value="Makanan" <?= ($menu['jenis'] == 'Makanan') ? 'selected' : '' ?>>Makanan</option>
                        <option value="Minuman" <?= ($menu['jenis'] == 'Minuman') ? 'selected' : '' ?>>Minuman</option>
                        <option value="Snack" <?= ($menu['jenis'] == 'Snack') ? 'selected' : '' ?>>Snack</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" value="<?= htmlspecialchars($menu['harga']) ?>" required>
                    </div>
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