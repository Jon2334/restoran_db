const fs = require('fs');

function fixMenuLinks(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Link-link aslinya mengandung /restoran_db/ yang cocok untuk localhost XAMPP,
        // tapi di Vercel path tersebut tidak ada dan akan menyebabkan 404 (Not Found).
        // Kita ubah agar mengarah ke root (/)
        
        content = content.replace(/href="\/restoran_db\//g, 'href="/');
        content = content.replace(/href="(\.\.\/)*restoran_db\//g, 'href="/');

        fs.writeFileSync(filePath, content);
        console.log(`Fixed links in ${filePath}`);
    }
}

fixMenuLinks('api/includes/header.php');
fixMenuLinks('includes/header.php');
