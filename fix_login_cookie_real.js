const fs = require('fs');

let auth = fs.readFileSync('api/auth/login.php', 'utf8');

// We need to bypass PHP sessions entirely on Vercel because file-based sessions are lost immediately 
// across different lambda instances.

auth = auth.replace("setcookie('user_id', $user['id'], time() + 86400, '/');", 
                    "setcookie('user_id', $user['id'], time() + 86400, '/', '', true, true);");

fs.writeFileSync('api/auth/login.php', auth);
