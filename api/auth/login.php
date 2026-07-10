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
require_once __DIR__ . '/../config/database.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    session_write_close();
            header("Location: /dashboard.php");
    exit();
}

$error = '';
$old_username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $old_username = htmlspecialchars($username);

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        // Cek user di database
        $stmt = $pdo->prepare('SELECT * FROM "user" WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            setcookie('user_id', $user['id'], time() + 86400, '/');
            $_SESSION['user_id'] = $user['id'];
            setcookie('username', $user['username'], time() + 86400, '/');
            $_SESSION['username'] = $user['username'];
            setcookie('nama', $user['nama'], time() + 86400, '/');
            $_SESSION['nama'] = $user['nama'];
            setcookie('level', $user['level'], time() + 86400, '/');
            $_SESSION['level'] = $user['level'];
            
            session_write_close();
            header("Location: /dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Restoran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f5f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .input-group-text {
            background-color: transparent;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-radius: 0.375rem;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #86b7fe;
        }
    </style>
</head>
<body>

<div class="card login-card p-4">
    <div class="text-center mb-4 mt-2">
        <div class="d-inline-block bg-primary bg-opacity-10 p-3 rounded-circle mb-3">
            <h2 class="text-primary mb-0"><i class="bi bi-shop"></i></h2>
        </div>
        <h3 class="fw-bold text-dark">Restoku</h3>
        <p class="text-muted small">Silakan login ke akun Anda</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i>
            <div>
                <?= htmlspecialchars($error) ?>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label small fw-semibold text-secondary">Username</label>
            <div class="input-group">
                <span class="input-group-text text-muted"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="username" name="username" value="<?= $old_username ?>" placeholder="Masukkan username" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
            <div class="input-group">
                <span class="input-group-text text-muted"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 fw-bold text-white mb-2">
            Login Sekarang <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </form>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>