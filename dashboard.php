<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// Ambil statistik untuk Dashboard
$jml_menu = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn();
$jml_pelanggan = $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$jml_karyawan = $pdo->query("SELECT COUNT(*) FROM karyawan")->fetchColumn();
$jml_transaksi = $pdo->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
$total_pendapatan = $pdo->query("SELECT SUM(total) FROM transaksi")->fetchColumn();
$total_pendapatan = $total_pendapatan ? $total_pendapatan : 0;

// Data untuk Grafik (Penjualan 7 hari terakhir)
$grafik_query = $pdo->query("
    SELECT tanggal, SUM(total) as pendapatan 
    FROM transaksi 
    
    GROUP BY tanggal 
    ORDER BY tanggal ASC
");
$tgl = [];
$pendapatan = [];
while ($row = $grafik_query->fetch()) {
    $tgl[] = date('d/m', strtotime($row['tanggal']));
    $pendapatan[] = $row['pendapatan'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Dashboard</h2>
</div>

<div class="row g-4 mb-4">
    <!-- Card Pendapatan -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-dollar fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Transaksi -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 border-start border-success border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Transaksi</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($jml_transaksi) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cart-check fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Pelanggan -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 border-start border-info border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Pelanggan</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($jml_pelanggan) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Menu -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 border-start border-warning border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Menu</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($jml_menu) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-card-list fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan (Semua Riwayat)</h6>
            </div>
            <div class="card-body">
                <canvas id="myChart" style="height: 300px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php
                    $trx_recent = $pdo->query("SELECT t.no_faktur, t.total, p.nama_pel FROM transaksi t JOIN pelanggan p ON t.kode_pel = p.kode_pel ORDER BY t.tanggal DESC, t.no_faktur DESC LIMIT 5");
                    while ($tr = $trx_recent->fetch()):
                    ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($tr['no_faktur']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($tr['nama_pel']) ?></small>
                        </div>
                        <span class="badge bg-success rounded-pill">Rp <?= number_format($tr['total'], 0, ',', '.') ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
                <div class="p-3 text-center">
                    <a href="transaksi/index.php" class="btn btn-sm btn-outline-primary">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($tgl) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($pendapatan) ?>,
                borderWidth: 2,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>