const { execSync } = require('child_process');
const envVar = 'DATABASE_URL="postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require"';

try {
  // Add to production
  execSync(`npx vercel env add DATABASE_URL production < <(echo "postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require")`, { stdio: 'inherit', shell: '/bin/bash' });
  
  // Add to preview/development
  execSync(`npx vercel env add DATABASE_URL preview < <(echo "postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require")`, { stdio: 'inherit', shell: '/bin/bash' });
  execSync(`npx vercel env add DATABASE_URL development < <(echo "postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require")`, { stdio: 'inherit', shell: '/bin/bash' });
} catch (e) {
  console.log("Mungkin environment variable sudah ada. Memulai deploy ulang...");
}
