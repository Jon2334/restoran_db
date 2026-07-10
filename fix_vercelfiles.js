const fs = require('fs');
const path = require('path');

// We have API folder for Vercel, let's make sure ALL files are identical in both root and API folder.
// Previously, we might have fixed something in root but forgot to copy to api/, or vice versa.

function copyDirRecursive(src, dest) {
    if (!fs.existsSync(dest)) fs.mkdirSync(dest, { recursive: true });
    
    let entries = fs.readdirSync(src, { withFileTypes: true });
    
    for (let entry of entries) {
        if (entry.name === 'api' || entry.name === '.git' || entry.name === 'node_modules') continue;
        
        let srcPath = path.join(src, entry.name);
        let destPath = path.join(dest, entry.name);
        
        if (entry.isDirectory()) {
            copyDirRecursive(srcPath, destPath);
        } else if (entry.name.endsWith('.php')) {
            fs.copyFileSync(srcPath, destPath);
        }
    }
}

copyDirRecursive('.', 'api');
console.log("Copied all PHP files to API directory for Vercel functions");
