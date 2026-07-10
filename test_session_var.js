const fs = require('fs');
const glob = require('glob'); // Not available in standard library, fallback to simple dir read

// Let's ensure session starts and header variables exist on all subpages.
// Since header.php now initializes session reliably, we just need to make sure every page correctly loads it.

function injectSessionToFiles(dir) {
    const items = fs.readdirSync(dir);
    for (const item of items) {
        const fullPath = dir + '/' + item;
        if (fs.statSync(fullPath).isDirectory()) {
            injectSessionToFiles(fullPath);
        } else if (fullPath.endsWith('.php') && !fullPath.includes('header.php') && !fullPath.includes('footer.php') && !fullPath.includes('database.php')) {
            let content = fs.readFileSync(fullPath, 'utf8');
            // Check if file uses session but doesn't include header or start session
            // Actually, all these files already require header.php as seen in menu/index.php.
        }
    }
}
