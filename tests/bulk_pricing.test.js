const fs = require('fs');
const path = require('path');
const {execFileSync} = require('child_process');
const os = require('os');

test('bulk pricing endpoints roundtrip', () => {
  const dbPath = path.join(os.tmpdir(), 'bulk_test.sqlite');
  try { fs.unlinkSync(dbPath); } catch(e) {}
  const env = {...process.env, DB_FILE: dbPath};

  const payload = JSON.stringify({
    product_id: 1,
    tiers: [
      {min_qty:1,max_qty:10,unit_price:9.99},
      {min_qty:11,max_qty:20,unit_price:8.99}
    ]
  });

  execFileSync('php', ['assets/cPhp/update_bulk_pricing.php'], {input: payload, env});

  const script = `$_GET['product_id']=1; include '${path.resolve('assets/cPhp/get_bulk_pricing.php')}';`;
  const out = execFileSync('php', ['-r', script], {env});
  const rows = JSON.parse(out.toString());
  expect(rows.length).toBe(2);
  expect(rows[0].unit_price).toBe(9.99);
});
