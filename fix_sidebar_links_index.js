const fs = require('fs');

function fixMenuDirectoryLinks(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Vercel kadang-kadang membingungkan /menu dengan /menu/index.php. 
        // Agar benar-benar tidak ada error "403 Forbidden" (karena mencoba membuka folder tanpa file index yang dikenal otomatis), 
        // kita pastikan SEMUA tautan menggunakan akhiran /index.php secara tertulis eksplisit.
        
        content = content.replace(/href="\/menu\/"/g, 'href="/menu/index.php"');
        content = content.replace(/href="\/pelanggan\/"/g, 'href="/pelanggan/index.php"');
        content = content.replace(/href="\/karyawan\/"/g, 'href="/karyawan/index.php"');
        content = content.replace(/href="\/transaksi\/"/g, 'href="/transaksi/index.php"');
        content = content.replace(/href="\/laporan\/"/g, 'href="/laporan/index.php"');

        fs.writeFileSync(filePath, content);
        console.log(`Explicit index.php links set in ${filePath}`);
    }
}

fixMenuDirectoryLinks('api/includes/header.php');
fixMenuDirectoryLinks('includes/header.php');
