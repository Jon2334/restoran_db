<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'semua';
$filter_tgl = isset($_GET['filter_tgl']) ? $_GET['filter_tgl'] : date('Y-m-d');
$filter_bulan = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : date('Y-m');
$filter_tahun = isset($_GET['filter_tahun']) ? $_GET['filter_tahun'] : date('Y');

$where = "1=1";
$params = [];

if ($filter_type == 'tanggal') {
    $where = "t.tanggal = ?";
    $params = [$filter_tgl];
} elseif ($filter_type == 'bulan') {
    $where = "DATE_FORMAT(t.tanggal, '%Y-%m') = ?";
    $params = [$filter_bulan];
} elseif ($filter_type == 'tahun') {
    $where = "YEAR(t.tanggal) = ?";
    $params = [$filter_tahun];
}

$query = "SELECT t.*, p.nama_pel FROM transaksi t JOIN pelanggan p ON t.kode_pel = p.kode_pel WHERE $where ORDER BY t.tanggal DESC, t.no_faktur DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transaksi = $stmt->fetchAll();

$total_pendapatan = 0;
foreach ($transaksi as $row) {
    $total_pendapatan += $row['total'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tampilkan Berdasarkan</label>
                <select name="filter_type" id="filter_type" class="form-select" onchange="toggleFilter()">
                    <option value="semua" <?= $filter_type == 'semua' ? 'selected' : '' ?>>Semua Transaksi</option>
                    <option value="tanggal" <?= $filter_type == 'tanggal' ? 'selected' : '' ?>>Per Tanggal</option>
                    <option value="bulan" <?= $filter_type == 'bulan' ? 'selected' : '' ?>>Per Bulan</option>
                    <option value="tahun" <?= $filter_type == 'tahun' ? 'selected' : '' ?>>Per Tahun</option>
                </select>
            </div>
            
            <div class="col-md-3 filter-input" id="div_tanggal" style="display: <?= $filter_type == 'tanggal' ? 'block' : 'none' ?>;">
                <label class="form-label">Pilih Tanggal</label>
                <input type="date" name="filter_tgl" class="form-control" value="<?= $filter_tgl ?>">
            </div>
            
            <div class="col-md-3 filter-input" id="div_bulan" style="display: <?= $filter_type == 'bulan' ? 'block' : 'none' ?>;">
                <label class="form-label">Pilih Bulan</label>
                <input type="month" name="filter_bulan" class="form-control" value="<?= $filter_bulan ?>">
            </div>
            
            <div class="col-md-3 filter-input" id="div_tahun" style="display: <?= $filter_type == 'tahun' ? 'block' : 'none' ?>;">
                <label class="form-label">Pilih Tahun</label>
                <select name="filter_tahun" class="form-select">
                    <?php 
                    $thn_sekarang = date('Y');
                    for($i = $thn_sekarang; $i >= $thn_sekarang - 5; $i--) {
                        $sel = ($filter_tahun == $i) ? 'selected' : '';
                        echo "<option value='$i' $sel>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tampilkan</button>
                <button type="button" class="btn btn-success ms-2" onclick="cetakLaporan()"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4" id="areaCetak">
    <div class="card-body">
        <div class="text-center mb-4 d-none d-print-block">
            <h3 class="mb-0">LAPORAN PENJUALAN RESTOKU</h3>
            <p class="text-muted">
                <?php
                if ($filter_type == 'semua') echo "Semua Periode";
                elseif ($filter_type == 'tanggal') echo "Tanggal: " . date('d F Y', strtotime($filter_tgl));
                elseif ($filter_type == 'bulan') echo "Bulan: " . date('F Y', strtotime($filter_bulan . '-01'));
                elseif ($filter_type == 'tahun') echo "Tahun: " . $filter_tahun;
                ?>
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">No Faktur</th>
                        <th width="15%">Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th width="20%" class="text-end">Total Pembelian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transaksi) > 0): ?>
                        <?php $no = 1; foreach ($transaksi as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['no_faktur']) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($row['nama_pel']) ?></td>
                            <td class="text-end">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary">
                            <td colspan="4" class="text-end fw-bold fs-6">TOTAL KESELURUHAN</td>
                            <td class="text-end fw-bold fs-6">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleFilter() {
        const val = document.getElementById('filter_type').value;
        document.querySelectorAll('.filter-input').forEach(el => el.style.display = 'none');
        if (val !== 'semua') {
            document.getElementById('div_' + val).style.display = 'block';
        }
    }

    function cetakLaporan() {
        const area = document.getElementById('areaCetak').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Cetak Laporan</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<style>@media print { .d-print-block { display: block !important; } } body { padding: 20px; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(area);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>