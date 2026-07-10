const fs = require('fs');

function fixHeaderSession(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        // Replace generic session_start with a safe check
        content = content.replace(/<\?php\s+session_start\(\);/g, "<?php\nif (session_status() === PHP_SESSION_NONE) {\n    session_start();\n}");
        fs.writeFileSync(filePath, content);
        console.log(`Fixed session start in ${filePath}`);
    }
}

fixHeaderSession('api/includes/header.php');
fixHeaderSession('includes/header.php');
