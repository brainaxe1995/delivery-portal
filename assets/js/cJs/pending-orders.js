// pending_orders.js

let currentPage = 1;
let totalPages  = 1;
const PER_PAGE = 20;

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

$(document).ready(function() {
  // Read ?page= from URL if present
  const params   = new URLSearchParams(window.location.search);
  const pageParm = parseInt(params.get('page'), 10) || 1;
  fetchPendingOrders(pageParm);
});

function fetchPendingOrders(page) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_pending_orders.php`,
    method: 'GET',
    data: { page, per_page: PER_PAGE },
    dataType: 'json',
    success(orderList, textStatus, xhr) {
      // Table rows
      renderTable(orderList);

      // Pagination header
      const tp = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      totalPages = tp;

      buildPagination('.base-pagination', totalPages, currentPage, fetchPendingOrders);
      updateUrl(page);
    },
    error(xhr, textStatus, errorThrown) {
      console.error('Error fetching pending orders:', textStatus, errorThrown);
      alert('❌ Could not load pending orders');
    },
  });
}

function renderTable(orders) {
  const $tbody = $('table tbody').empty();

  orders.forEach(order => {
    // Build display values
    const orderId    = `#${escapeHtml(order.id)}`;
    const name       = escapeHtml([order.billing.first_name, order.billing.last_name].filter(Boolean).join(' ') || 'No Name');
    const email      = escapeHtml(order.billing.email || 'No Email');
    const dateText   = order.date_created
      ? escapeHtml(new Date(order.date_created).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' }))
      : 'No Date';
    const itemsHtml  = order.line_items.length
      ? order.line_items.map(i => `<p>${escapeHtml(i.quantity)}× ${escapeHtml(i.name)}</p>`).join('')
      : '<p>No Items</p>';
    const currentSt  = order.status || 'unknown';
    const label      = escapeHtml(getStatusLabel(currentSt));

    // Build status dropdown
    const dropdown = `
      <div class="btn-group">
        <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
          <span class="status-text">${label}</span>
        </button>
        <ul class="dropdown-menu">
          ${['pending','processing','on-hold','in-transit','completed','returned','refunded','cancelled']
            .map(st => `<li><a class="dropdown-item" href="#" data-status="${st}">${escapeHtml(getStatusLabel(st))}</a></li>`)
            .join('')}
        </ul>
      </div>
    `;

    // Pre-fill tracking code if present
    const existingMeta = order.meta_data.find(m => m.key === '_tracking_number');
    const initTrack    = existingMeta ? escapeHtml(existingMeta.value) : '';

    // Row HTML
    const row = `
      <tr data-order-id="${order.id}">
        <td>${orderId} ${name}</td>
        <td><a href="mailto:${email}">${email}</a></td>
        <td>${dateText}</td>
        <td class="items-col">${itemsHtml}</td>
        <td>${dropdown}</td>
        <td>
          <form class="tracking-form d-flex">
            <input type="text" name="tracking_code" class="form-control form-control-sm tracking-code-input"
                   placeholder="Tracking code" value="${initTrack}">
            <button type="submit" class="btn btn-link p-0 ms-2" title="Save">
              <i class="lni lni-checkmark-circle" style="font-size:1.4rem;color:#28a745"></i>
            </button>
          </form>
        </td>
      </tr>
    `;

    $tbody.append(row);
  });

  // Status change handler
  $tbody.find('.dropdown-item').click(function(e) {
    e.preventDefault();
    const $li    = $(this);
    const newSt  = $li.data('status');
    const $btnGrp= $li.closest('.btn-group');
    const $btn   = $btnGrp.find('.status-text');
    const orderId= $li.closest('tr').data('order-id');

    // Update UI immediately
    $btn.text(getStatusLabel(newSt));

    // Persist to server
    const $toggle = $btnGrp.find('.dropdown-toggle');
    $toggle.prop('disabled', true);
    $.ajax({
      url: `${BASE_URL}/assets/cPhp/update_order.php`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ order_id: orderId, status: newSt })
    })
    .done(() => fetchPendingOrders(currentPage))
    .fail(xhr => {
      console.error(xhr.responseText);
      alert('❌ Status update failed');
    })
    .always(() => {
      $toggle.prop('disabled', false);
    });
  });

  // Tracking code form handler
  $tbody.find('.tracking-form').submit(function(e) {
    e.preventDefault();
    const $form      = $(this);
    const codeVal    = $form.find('[name="tracking_code"]').val().trim();
    const orderId    = $form.closest('tr').data('order-id');

    const $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true);

    $.ajax({
      url: `${BASE_URL}/assets/cPhp/update_order.php`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ order_id: orderId, tracking_code: codeVal })
    })
    .done(() => fetchPendingOrders(currentPage))
    .fail(xhr => {
      console.error(xhr.responseText);
      alert('❌ Failed to save tracking code');
    })
    .always(() => {
      $btn.prop('disabled', false);
    });
  });
}

function getStatusLabel(key) {
  const map = {
    pending:    'New Order',
    processing: 'Processing',
    'on-hold':  'On Hold',
    'in-transit':'In Transit',
    completed:  'Delivered',
    returned:   'Returned',
    refunded:   'Refunded',
    cancelled:  'Cancelled'
  };
  return map[key] || key;
}

function updateUrl(page) {
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.pushState({}, '', newUrl);
}
