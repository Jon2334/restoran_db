const fs = require('fs');

// Vercel serverless functions handle cookies slightly differently across requests.
// We need to make sure the session is saved and propagated properly.

let content = fs.readFileSync('api/auth/login.php', 'utf8');

// Ensure session is written before redirect
content = content.replace(/header\("Location: \/dashboard\.php"\);/g, "session_write_close();\n            header(\"Location: /dashboard.php\");");

fs.writeFileSync('api/auth/login.php', content);
