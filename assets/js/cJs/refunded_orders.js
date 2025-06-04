// assets/js/cJs/refunded_orders.js

let currentPage = 1;
let totalPages  = 1;
const PER_PAGE  = 20;

$(document).ready(function() {
  const params   = new URLSearchParams(window.location.search);
  const pageParm = parseInt(params.get('page'), 10) || 1;
  fetchRefundedOrders(pageParm);
});

function fetchRefundedOrders(page) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_refunded_orders.php`,
    method: 'GET',
    data: { page, per_page: PER_PAGE },
    dataType: 'json',
    success(list, _, xhr) {
      renderTable(list);
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      buildPagination();                // no args needed now
      updateUrl(page);
    },
    error(xhr, status, err) {
      console.error('Error fetching refunded orders:', status, err);
      alert('❌ Could not load refunded orders');
    }
  });
}

function renderTable(orders) {
  const $tbody = $('table tbody').empty();

  orders.forEach(order => {
    const orderId   = `#${order.id}`;
    const name      = [order.billing.first_name, order.billing.last_name]
                      .filter(Boolean).join(' ') || 'No Name';
    const email     = order.billing.email || 'No Email';
    const dateText  = order.date_created
      ? new Date(order.date_created)
          .toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })
      : 'No Date';
    const itemsHtml = order.line_items.length
      ? order.line_items.map(i => `<p>${i.quantity}× ${i.name}</p>`).join('')
      : '<p>No Items</p>';
    const currentSt = order.status || 'unknown';

    // Status dropdown
    const dropdown = `
      <div class="btn-group">
        <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
          <span class="status-text">${getStatusLabel(currentSt)}</span>
        </button>
        <ul class="dropdown-menu">
          ${['pending','processing','on-hold','in-transit','completed','returned','refunded','cancelled']
            .map(st => `<li><a class="dropdown-item" href="#" data-status="${st}">${getStatusLabel(st)}</a></li>`)
            .join('')}
        </ul>
      </div>
    `;

    // Tracking code form
    const existingMeta = (order.meta_data || []).find(m => m.key === '_tracking_number');
    const initTrack    = existingMeta ? existingMeta.value : '';
    const tracking = `
      <form class="tracking-form d-flex">
        <input
          type="text"
          name="tracking_code"
          class="form-control form-control-sm tracking-code-input"
          placeholder="Tracking code"
          value="${initTrack}"
        >
        <button type="submit" class="btn btn-link p-0 ms-2" title="Save">
          <i class="lni lni-checkmark-circle" style="font-size:1.4rem;color:#28a745"></i>
        </button>
      </form>
    `;

    $tbody.append(`
      <tr data-order-id="${order.id}">
        <td>${orderId} ${name}</td>
        <td><a href="mailto:${email}">${email}</a></td>
        <td>${dateText}</td>
        <td class="items-col">${itemsHtml}</td>
        <td>${dropdown}</td>
        <td>${tracking}</td>
      </tr>
    `);
  });

  // Status‐change handler
  $tbody.find('.dropdown-item').off('click').on('click', function(e) {
    e.preventDefault();
    const newSt   = $(this).data('status');
    const $btnTxt = $(this).closest('.btn-group').find('.status-text');
    const orderId = $(this).closest('tr').data('order-id');

    $btnTxt.text(getStatusLabel(newSt));

    $.ajax({
      url: `${BASE_URL}/assets/cPhp/update_order.php`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ order_id: orderId, status: newSt })
    })
    .done(() => alert(`✅ Status updated to "${getStatusLabel(newSt)}"`))
    .fail(xhr => {
      console.error(xhr.responseText);
      alert('❌ Status update failed');
    });
  });

  // Tracking‐form submit handler
  $tbody.find('.tracking-form').off('submit').on('submit', function(e) {
    e.preventDefault();
    const orderId = $(this).closest('tr').data('order-id');
    const codeVal = $(this).find('[name="tracking_code"]').val().trim();

    $.ajax({
      url: `${BASE_URL}/assets/cPhp/update_order.php`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ order_id: orderId, tracking_code: codeVal })
    })
    .done(() => alert('✅ Tracking code saved'))
    .fail(xhr => {
      console.error(xhr.responseText);
      alert('❌ Failed to save tracking code');
    });
  });
}

function getStatusLabel(key) {
  const map = {
    pending:     'New Order',
    processing:  'Processing',
    'on-hold':   'On Hold',
    'in-transit':'In Transit',
    completed:   'Delivered',
    returned:    'Returned',
    refunded:    'Refunded',
    cancelled:   'Cancelled'
  };
  return map[key] || key;
}

function updateUrl(page) {
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.pushState({}, '', newUrl);
}
