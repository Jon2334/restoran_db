const { Client } = require('pg');
const fs = require('fs');

const connectionString = 'postgresql://neondb_owner:npg_Odk2c4EgSVXH@ep-green-mode-aonww1jr.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require';

const client = new Client({
  connectionString,
});

async function run() {
  try {
    await client.connect();
    console.log('Connected to Neon DB');
    
    const sql = fs.readFileSync('database/db_postgres.sql', 'utf8');
    await client.query(sql);
    
    console.log('Database schema and dummy data imported successfully!');
  } catch (err) {
    console.error('Error executing query', err.stack);
  } finally {
    await client.end();
  }
}

run();
