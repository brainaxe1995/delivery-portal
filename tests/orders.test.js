describe('orders.js renderOrders', () => {
  let renderOrders;

  beforeEach(() => {
    document.body.innerHTML = '<table><tbody id="orders-body"></tbody></table>';
    delete require.cache[require.resolve('jquery')];
    global.$ = require('jquery');
    global.$.ajax = jest.fn(opts => {
      if (opts.success) opts.success([]);
      if (opts.complete) opts.complete({ getResponseHeader: () => '1' });
    });
    delete require.cache[require.resolve('../assets/js/cJs/orders.js')];
    const mod = require('../assets/js/cJs/orders.js');
    renderOrders = mod.renderOrders;
  });

  afterEach(() => {
    delete global.$;
  });

  test('shows message when no orders', () => {
    renderOrders([]);
    const text = document.querySelector('#orders-body').textContent;
    expect(text).toContain('No orders to display');
  });

  test('adds a row for each order', () => {
    const orders = [{
      id: 1,
      date_created: '2024-01-01T00:00:00Z',
      billing: { first_name: 'John', last_name: 'Doe' },
      total: '12.34',
      status: 'new',
      meta_data: []
    }];
    renderOrders(orders);
    const rows = document.querySelectorAll('#orders-body tr');
    expect(rows.length).toBe(1);
    const rowText = rows[0].textContent;
    expect(rowText).toContain('#1');
    expect(rowText).toContain('AED 12.34');
  });
});
