const fs = require('fs');
const path = require('path');
const vm = require('vm');
const jquery = require('jquery');

let script;

beforeAll(() => {
  script = fs.readFileSync(path.resolve(__dirname, '../assets/js/cJs/pagination.js'), 'utf8');
});

beforeEach(() => {
  // Jest uses jsdom environment by default
  global.$ = jquery(global.window);
  document.body.innerHTML = '<ul class="base-pagination pagination"></ul>';
  vm.runInThisContext(script);
});

afterEach(() => {
  delete global.$;
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
