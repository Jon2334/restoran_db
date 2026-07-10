const fs = require('fs');

function fixCSS(filePath) {
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Cek jika stylesheet belum sempurna
        if (!content.includes('font-family:')) {
            const fontStyles = `
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
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
            background: #212529;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 1.5rem;
            background: #1a1e21;
        }
        #sidebar ul.components {
            padding: 1rem 0;
        }
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
        }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        #sidebar .nav-link {
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #0d6efd;
        }
        #sidebar .nav-link i { margin-right: 0.5rem; }
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
`;
            // Ganti blok style lama dengan yang lebih lengkap
            content = content.replace(/body\s*\{[^}]*\}\s*\.wrapper\s*\{[^}]*\}\s*#sidebar\s*\{[^}]*\}/g, fontStyles);
            fs.writeFileSync(filePath, content);
            console.log(`Injected full CSS to ${filePath}`);
        }
    }
}

fixCSS('api/includes/header.php');
fixCSS('includes/header.php');
