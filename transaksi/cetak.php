<?php
require_once __DIR__ . '/../config/database.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit();
}

$no_faktur = $_GET['id'];

// Ambil data header
$stmt_header = $pdo->prepare("SELECT t.*, p.nama_pel, p.alamat FROM transaksi t JOIN pelanggan p ON t.kode_pel = p.kode_pel WHERE t.no_faktur = ?");
$stmt_header->execute([$no_faktur]);
$trx = $stmt_header->fetch();

if (!$trx) {
    echo "Data transaksi tidak ditemukan.";
    exit();
}

// Ambil data detail
$stmt_detail = $pdo->prepare("SELECT d.*, m.nama_menu FROM detail_transaksi d JOIN menu m ON d.kode_menu = m.kode_menu WHERE d.no_faktur = ?");
$stmt_detail->execute([$no_faktur]);
$details = $stmt_detail->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk - <?= htmlspecialchars($no_faktur) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font mirip struk kasir */
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .struk {
            width: 80mm; /* Lebar standar printer thermal */
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
        }
        .info {
            margin-bottom: 10px;
        }
        .info table {
            width: 100%;
        }
        .info table td {
            padding: 2px 0;
        }
        .table-item {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-item th {
            text-align: left;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }
        .table-item td {
            padding: 5px 0;
            vertical-align: top;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .total-area {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        .total-area table {
            width: 100%;
        }
        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .struk {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="struk">
    <div class="header">
        <h2>RESTOKU</h2>
        <p>Jl. Contoh Restoran No. 123</p>
        <p>Telp: 0812-3456-7890</p>
    </div>
    
    <div class="info">
        <table>
            <tr>
                <td width="35%">No Faktur</td>
                <td width="5%">:</td>
                <td><?= htmlspecialchars($trx['no_faktur']) ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>:</td>
                <td><?= htmlspecialchars($trx['nama_pel']) ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td><?= htmlspecialchars($_SESSION['nama']) ?></td>
            </tr>
        </table>
    </div>

    <table class="table-item">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($details as $d): ?>
            <tr>
                <td colspan="3"><?= htmlspecialchars($d['nama_menu']) ?></td>
            </tr>
            <tr>
                <td style="color: #666; padding-left:10px;">@ <?= number_format($d['harga'], 0, ',', '.') ?></td>
                <td class="text-center"><?= $d['jumlah'] ?></td>
                <td class="text-right"><?= number_format($d['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-area">
        <table>
            <tr>
                <td style="font-weight: bold; font-size: 14px;">TOTAL BELANJA</td>
                <td class="text-right" style="font-weight: bold; font-size: 14px;">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>