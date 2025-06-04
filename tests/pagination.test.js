const fs = require('fs');
const path = require('path');
const vm = require('vm');
const { JSDOM } = require('jsdom');

let script;

beforeAll(() => {
  script = fs.readFileSync(path.resolve(__dirname, '../assets/js/cJs/pagination.js'), 'utf8');
});

beforeEach(() => {
  const dom = new JSDOM('<!doctype html><html><body></body></html>');
  global.window = dom.window;
  global.document = dom.window.document;
  // Load jQuery with the newly created window
  delete require.cache[require.resolve('jquery')];
  const jquery = require('jquery');
  global.$ = jquery;
  document.body.innerHTML = '<ul class="base-pagination pagination"></ul>';
  vm.runInNewContext(script, global);
});

afterEach(() => {
  delete global.$;
  delete global.window;
  delete global.document;
  delete global.buildPagination;
  delete global.currentPage;
  delete global.totalPages;
});

test('generates expected number of pagination links', () => {
  global.currentPage = 2;
  global.totalPages = 5;
  buildPagination();
  const items = document.querySelectorAll('.base-pagination.pagination li');
  expect(items.length).toBe(7); // prev + pages + next
});
