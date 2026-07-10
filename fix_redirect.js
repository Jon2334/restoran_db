const fs = require('fs');

function fixHeaderRedirect(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        content = content.replace(/header\("Location: \.\.\/auth\/login\.php"\);/g, 'header("Location: /auth/login.php");');
        fs.writeFileSync(filePath, content);
        console.log(`Fixed redirect in ${filePath}`);
    }
}

fixHeaderRedirect('api/includes/header.php');
fixHeaderRedirect('includes/header.php');
