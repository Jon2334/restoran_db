<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

// Generate Nomor Faktur Otomatis
$tgl_hari_ini = date('Ymd');
$stmt_faktur = $pdo->query("SELECT no_faktur FROM transaksi WHERE no_faktur LIKE 'TRX-$tgl_hari_ini-%' ORDER BY no_faktur DESC LIMIT 1");
$last_faktur = $stmt_faktur->fetchColumn();
if ($last_faktur) {
    $num = (int) substr($last_faktur, -3);
    $new_faktur = 'TRX-' . $tgl_hari_ini . '-' . sprintf('%03d', $num + 1);
} else {
    $new_faktur = 'TRX-' . $tgl_hari_ini . '-001';
}

// Ambil data pelanggan dan menu untuk dropdown
$pelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama_pel ASC")->fetchAll();
$menu = $pdo->query("SELECT * FROM menu ORDER BY nama_menu ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Input Transaksi</h1>
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row">
    <!-- Form Transaksi Header & Detail -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
            </div>
            <div class="card-body">
                <form id="formTambahItem">
                    <div class="mb-3">
                        <label for="no_faktur" class="form-label">No Faktur</label>
                        <input type="text" class="form-control" id="no_faktur" value="<?= $new_faktur ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" value="<?= date('Y-m-d') ?>" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="kode_pel" class="form-label">Pelanggan</label>
                        <select class="form-select" id="kode_pel" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($pelanggan as $p): ?>
                                <option value="<?= $p['kode_pel'] ?>"><?= $p['nama_pel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <h6 class="font-weight-bold mb-3">Tambah Item</h6>
                    <div class="mb-3">
                        <label for="kode_menu" class="form-label">Menu</label>
                        <select class="form-select" id="kode_menu" required onchange="setHarga()">
                            <option value="" data-harga="0" data-nama="">-- Pilih Menu --</option>
                            <?php foreach ($menu as $m): ?>
                                <option value="<?= $m['kode_menu'] ?>" data-harga="<?= $m['harga'] ?>" data-nama="<?= $m['nama_menu'] ?>">
                                    <?= $m['nama_menu'] ?> (Rp <?= number_format($m['harga'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" readonly>
                        </div>
                        <div class="col">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" min="1" value="1" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="tambahItem()"><i class="bi bi-cart-plus"></i> Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Keranjang Belanja -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Keranjang Belanja</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="tabelKeranjang">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">Kode</th>
                                <th>Nama Menu</th>
                                <th width="20%">Harga</th>
                                <th width="10%">Qty</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Item akan ditambahkan via JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Akhir:</th>
                                <th colspan="2" class="fs-5 text-primary" id="totalAkhirDisplay">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <form action="proses.php" method="POST" id="formSimpanTransaksi">
                    <input type="hidden" name="no_faktur" value="<?= $new_faktur ?>">
                    <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
                    <input type="hidden" name="kode_pel" id="hidden_kode_pel">
                    <input type="hidden" name="total" id="hidden_total" value="0">
                    <div id="hidden_items"></div>
                    
                    <button type="button" class="btn btn-success float-end mt-3 px-4 py-2 fw-bold" onclick="simpanTransaksi()"><i class="bi bi-check-circle"></i> Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let keranjang = [];

    function setHarga() {
        const select = document.getElementById('kode_menu');
        const harga = select.options[select.selectedIndex].getAttribute('data-harga');
        document.getElementById('harga').value = harga;
    }

    function tambahItem() {
        const select = document.getElementById('kode_menu');
        const kodeMenu = select.value;
        if (!kodeMenu) {
            alert('Pilih menu terlebih dahulu!');
            return;
        }

        const namaMenu = select.options[select.selectedIndex].getAttribute('data-nama');
        const harga = parseFloat(document.getElementById('harga').value);
        const jumlah = parseInt(document.getElementById('jumlah').value);
        
        if (jumlah <= 0 || isNaN(jumlah)) {
            alert('Jumlah tidak valid!');
            return;
        }

        const subtotal = harga * jumlah;

        // Cek apakah item sudah ada di keranjang
        const existIndex = keranjang.findIndex(item => item.kode === kodeMenu);
        if (existIndex > -1) {
            keranjang[existIndex].jumlah += jumlah;
            keranjang[existIndex].subtotal = keranjang[existIndex].harga * keranjang[existIndex].jumlah;
        } else {
            keranjang.push({
                kode: kodeMenu,
                nama: namaMenu,
                harga: harga,
                jumlah: jumlah,
                subtotal: subtotal
            });
        }

        renderKeranjang();
        
        // Reset form input item
        document.getElementById('kode_menu').value = '';
        document.getElementById('harga').value = '';
        document.getElementById('jumlah').value = '1';
    }

    function hapusItem(index) {
        keranjang.splice(index, 1);
        renderKeranjang();
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function renderKeranjang() {
        const tbody = document.querySelector('#tabelKeranjang tbody');
        const hiddenItems = document.getElementById('hidden_items');
        tbody.innerHTML = '';
        hiddenItems.innerHTML = '';
        
        let total = 0;

        if (keranjang.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Keranjang kosong.</td></tr>';
        } else {
            keranjang.forEach((item, index) => {
                total += item.subtotal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="badge bg-secondary">${item.kode}</span></td>
                    <td>${item.nama}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    <td>${item.jumlah}</td>
                    <td class="fw-bold">${formatRupiah(item.subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem(${index})"><i class="bi bi-x"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);

                // Tambah ke hidden form inputs
                hiddenItems.innerHTML += `
                    <input type="hidden" name="items[${index}][kode_menu]" value="${item.kode}">
                    <input type="hidden" name="items[${index}][harga]" value="${item.harga}">
                    <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
                    <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal}">
                `;
            });
        }

        document.getElementById('totalAkhirDisplay').innerText = formatRupiah(total);
        document.getElementById('hidden_total').value = total;
    }

    function simpanTransaksi() {
        const kodePel = document.getElementById('kode_pel').value;
        if (!kodePel) {
            alert('Silakan pilih pelanggan terlebih dahulu!');
            document.getElementById('kode_pel').focus();
            return;
        }

        if (keranjang.length === 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }

        document.getElementById('hidden_kode_pel').value = kodePel;
        
        if(confirm('Apakah Anda yakin ingin menyimpan transaksi ini?')) {
            document.getElementById('formSimpanTransaksi').submit();
        }
    }

    // Inisialisasi tampilan
    renderKeranjang();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>