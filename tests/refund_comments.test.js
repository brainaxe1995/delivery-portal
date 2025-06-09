const fs = require('fs');
const path = require('path');
const {execFileSync} = require('child_process');
const os = require('os');

const dbPath = path.join(os.tmpdir(), 'refund_test.sqlite');

test('add and fetch refund comments', () => {
  try { fs.unlinkSync(dbPath); } catch(e) {}
  const env = {...process.env, DB_FILE: dbPath};
  execFileSync('php', ['assets/cPhp/add_refund_comment.php'], {input: JSON.stringify({refund_id:1,user_id:2,comment:"hello"}), env});
  const script = `$_GET['refund_id']=1; include '${path.resolve('assets/cPhp/get_refund_comments.php')}';`;
  const out = execFileSync('php', ['-r', script], {env});
  const rows = JSON.parse(out.toString());
  expect(rows[0].comment).toBe('hello');
});
