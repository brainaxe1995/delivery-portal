const fs = require('fs');
const path = require('path');
const {execFileSync} = require('child_process');
const os = require('os');

test('update_inventory_settings stores thresholds', () => {
  const dbPath = path.join(os.tmpdir(), 'inv_test.sqlite');
  try { fs.unlinkSync(dbPath); } catch(e) {}
  const env = {...process.env, DB_FILE: dbPath};
  const payload = JSON.stringify({product_id:5,safety_stock:10,reorder_threshold:3});
  execFileSync('php', ['assets/cPhp/update_inventory_settings.php'], {input:payload, env});
  const out = execFileSync('sqlite3', [dbPath, "SELECT safety_stock,reorder_threshold FROM inventory_settings WHERE product_id=5;"]);
  expect(out.toString().trim()).toBe('10|3');
});
