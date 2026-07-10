<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
        }
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
        }
        #sidebar.active {
            margin-left: calc(var(--sidebar-width) * -1);
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #212529;
            border-bottom: 1px solid #4b545c;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.1em;
            display: block;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            transition: 0.3s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: #007bff;
        }
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .submenu {
            background: #2b3035;
        }
        .submenu a {
            padding-left: 50px !important;
            font-size: 0.95em !important;
        }
        
        #content {
            width: 100%;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .top-navbar {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-content {
            padding: 25px;
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center">
            <i class="bi bi-shop fs-3 me-2 text-primary"></i>
            <h4 class="mb-0 fw-bold">Restoku</h4>
        </div>

        <div class="p-3 border-bottom border-secondary">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['nama']) ?></h6>
                    <small class="text-info"><?= htmlspecialchars($_SESSION['level']) ?></small>
                </div>
            </div>
        </div>

        <ul class="list-unstyled components">
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                <a href="/restoran_db/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </li>
            
            <li>
                <a href="#masterSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-database"></i> Master Data</span>
                </a>
                <ul class="collapse list-unstyled submenu <?= in_array(basename(dirname($_SERVER['PHP_SELF'])), ['menu', 'pelanggan', 'karyawan']) ? 'show' : '' ?>" id="masterSubmenu">
                    <li class="<?= basename(dirname($_SERVER['PHP_SELF'])) == 'menu' ? 'active' : '' ?>">
                        <a href="/restoran_db/menu/index.php">Data Menu</a>
                    </li>
                    <li class="<?= basename(dirname($_SERVER['PHP_SELF'])) == 'pelanggan' ? 'active' : '' ?>">
                        <a href="/restoran_db/pelanggan/index.php">Data Pelanggan</a>
                    </li>
                    <li class="<?= basename(dirname($_SERVER['PHP_SELF'])) == 'karyawan' ? 'active' : '' ?>">
                        <a href="/restoran_db/karyawan/index.php">Data Karyawan</a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="#transaksiSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cart3"></i> Transaksi</span>
                </a>
                <ul class="collapse list-unstyled submenu <?= basename(dirname($_SERVER['PHP_SELF'])) == 'transaksi' ? 'show' : '' ?>" id="transaksiSubmenu">
                    <li class="<?= (basename(dirname($_SERVER['PHP_SELF'])) == 'transaksi' && basename($_SERVER['PHP_SELF']) == 'tambah.php') ? 'active' : '' ?>">
                        <a href="/restoran_db/transaksi/tambah.php">Input Transaksi</a>
                    </li>
                    <li class="<?= (basename(dirname($_SERVER['PHP_SELF'])) == 'transaksi' && basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
                        <a href="/restoran_db/transaksi/index.php">Riwayat Transaksi</a>
                    </li>
                </ul>
            </li>
            
            <li class="<?= basename(dirname($_SERVER['PHP_SELF'])) == 'laporan' ? 'active' : '' ?>">
                <a href="/restoran_db/laporan/index.php"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
            </li>
            
            <li>
                <a href="/restoran_db/auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3"><?= date('l, d F Y') ?></span>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama']) ?>&background=0D8ABC&color=fff" alt="mdo" width="32" height="32" class="rounded-circle me-2">
                        <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/restoran_db/auth/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
