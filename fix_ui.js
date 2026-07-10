const fs = require('fs');

// Add Chart.js to footer
let footer = fs.readFileSync('includes/footer.php', 'utf8');
if (!footer.includes('chart.js')) {
    footer = footer.replace(
        '<!-- Bootstrap JS -->', 
        '<!-- Chart.js -->\n<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>\n<!-- Bootstrap JS -->'
    );
    fs.writeFileSync('includes/footer.php', footer);
    fs.writeFileSync('api/includes/footer.php', footer);
    console.log("Added Chart.js to footer");
}

// Enhance the main UI styling in header.php
let header = fs.readFileSync('includes/header.php', 'utf8');
if (!header.includes('box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);')) {
    const customStyles = `
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
        }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .sidebar-heading {
            text-align: center;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
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
    
    header = header.replace('</style>', customStyles + '\n    </style>');
    fs.writeFileSync('includes/header.php', header);
    fs.writeFileSync('api/includes/header.php', header);
    console.log("Enhanced UI styling in header.php");
}
