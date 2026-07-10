const fs = require('fs');

let dashboardSource = fs.readFileSync('dashboard.php', 'utf8');

// The original dashboard.php doesn't have session_start() or redirect checks, it relies on includes/header.php
// which we just fixed.
// Let's replace api/dashboard.php with the exact content of dashboard.php so it has the full UI.
fs.writeFileSync('api/dashboard.php', dashboardSource);

console.log("api/dashboard.php updated with full dashboard content");
