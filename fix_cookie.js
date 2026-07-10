const fs = require('fs');

function setCookieParams(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Remove existing session_start blocks
        content = content.replace(/<\?php\s+session_start\(\);/g, "<?php");
        content = content.replace(/<\?php\nif\s*\(session_status\(\)\s*===\s*PHP_SESSION_NONE\)\s*\{\n\s*session_start\(\);\n\}/g, "<?php");
        
        // Inject a robust serverless-compatible session configuration
        const sessionConfig = `<?php
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
}`;
        content = content.replace(/<\?php/i, sessionConfig);
        fs.writeFileSync(filePath, content);
        console.log(`Updated session config in ${filePath}`);
    }
}

setCookieParams('api/includes/header.php');
setCookieParams('includes/header.php');
setCookieParams('api/auth/login.php');
setCookieParams('auth/login.php');
setCookieParams('api/index.php');
setCookieParams('index.php');
