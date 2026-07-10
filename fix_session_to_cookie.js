const fs = require('fs');

// Auth Login Fix - menggunakan Cookie tanpa mengenkripsi karena kita butuh cara cepat untuk Vercel. 
// JWT idealnya tapi lebih kompleks. Kita set session di Vercel menggunakan native Cookies
function rewriteAuth() {
    let auth = fs.readFileSync('api/auth/login.php', 'utf8');
    auth = auth.replace(/\$_SESSION\['user_id'\] = \$user\['id'\];/g, "setcookie('user_id', $user['id'], time() + 86400, '/');\n            $_SESSION['user_id'] = $user['id'];");
    auth = auth.replace(/\$_SESSION\['username'\] = \$user\['username'\];/g, "setcookie('username', $user['username'], time() + 86400, '/');\n            $_SESSION['username'] = $user['username'];");
    auth = auth.replace(/\$_SESSION\['nama'\] = \$user\['nama'\];/g, "setcookie('nama', $user['nama'], time() + 86400, '/');\n            $_SESSION['nama'] = $user['nama'];");
    auth = auth.replace(/\$_SESSION\['level'\] = \$user\['level'\];/g, "setcookie('level', $user['level'], time() + 86400, '/');\n            $_SESSION['level'] = $user['level'];");
    
    // Redirect if already logged in via Cookie
    auth = auth.replace(/if \(isset\(\$_SESSION\['user_id'\]\)\) \{/g, "if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {");
    
    fs.writeFileSync('api/auth/login.php', auth);
    console.log("Auth Login updated to use regular cookies");
}

function rewriteHeader() {
    let headerFiles = ['api/includes/header.php', 'includes/header.php', 'api/dashboard.php', 'dashboard.php', 'api/index.php', 'index.php'];
    
    headerFiles.forEach(file => {
        if(fs.existsSync(file)) {
            let content = fs.readFileSync(file, 'utf8');
            content = content.replace(/if \(!isset\(\$_SESSION\['user_id'\]\)\) \{/g, "if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {");
            fs.writeFileSync(file, content);
            console.log(`Updated auth check in ${file}`);
        }
    });
}

function rewriteLogout() {
    let logoutFiles = ['api/auth/logout.php', 'auth/logout.php'];
    logoutFiles.forEach(file => {
        if(fs.existsSync(file)) {
            let content = fs.readFileSync(file, 'utf8');
            content = content.replace(/session_destroy\(\);/g, "session_destroy();\nsetcookie('user_id', '', time() - 3600, '/');\nsetcookie('username', '', time() - 3600, '/');\nsetcookie('nama', '', time() - 3600, '/');\nsetcookie('level', '', time() - 3600, '/');");
            fs.writeFileSync(file, content);
            console.log(`Updated logout in ${file}`);
        }
    });
}

rewriteAuth();
rewriteHeader();
rewriteLogout();
