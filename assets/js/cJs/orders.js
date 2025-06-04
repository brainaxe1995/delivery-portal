// portal/assets/js/cJs/orders.js

// map status → endpoint
const endpointMap = {
  new:          'get_processing_orders.php',
  pending:      'get_pending_orders.php',
  processing:   'get_processing_orders.php',
  'in-transit': 'get_in_transit_orders.php',
  completed:    'get_delivered_orders.php',
  returned:     'get_returned_orders.php',
  refunded:     'get_refunded_orders.php',
  cancelled:    'get_cancelled_orders.php'
};

let currentStatus = 'new';
let currentPage   = 1;
let totalPages    = 1;

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

// fetch + render
function fetchOrders(page = 1) {
  currentPage = page;
  console.log('Fetching', currentStatus, 'page', page);
  $.ajax({
    url: `${BASE_URL}/assets/cPhp/${endpointMap[currentStatus]}`,
    method: 'GET',
    data: { page, per_page: 20 },
    dataType: 'json',
    success(data) {
      renderOrders(data);
    },
    complete(xhr) {
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      console.log('Total pages =', totalPages);
      buildPagination();  // from pagination.js
      history.replaceState({}, '', `${location.pathname}?status=${currentStatus}&page=${currentPage}`);
    }
  });
}

// fill table
function renderOrders(orders) {
  const $body = $('#orders-body').empty();
  if (!orders.length) {
    return $body.append(`<tr><td colspan="6" class="text-center">No orders to display.</td></tr>`);
  }
  orders.forEach(o => {
    const date = new Date(o.date_created)
                   .toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
    // find existing tracking
    const meta  = (o.meta_data||[]).find(m => /tracking_number/i.test(m.key));
    const exist = meta ? meta.value : '';
    $body.append(`
      <tr>
        <td>#${escapeHtml(o.id)}</td>
        <td>${escapeHtml(date)}</td>
        <td>${escapeHtml((o.billing?.first_name||'') + ' ' + (o.billing?.last_name||''))}</td>
        <td>AED ${escapeHtml(parseFloat(o.total).toFixed(2))}</td>
        <td>${escapeHtml(o.status.replace('-', ' '))}</td>
        <td>
          <button class="btn btn-sm btn-success me-1"
                  onclick="openTracking(${escapeHtml(o.id)}, '${escapeHtml(exist)}')">Track</button>
          <button class="btn btn-sm btn-secondary"
                  onclick="openComment(${escapeHtml(o.id)})">Comment</button>
        </td>
      </tr>
    `);
  });
}

// expose for pagination.js
window.fetchNewOrders        = page => (currentStatus='new',        fetchOrders(page));
window.fetchPendingOrders    = page => (currentStatus='pending',    fetchOrders(page));
window.fetchProcessingOrders = page => (currentStatus='processing', fetchOrders(page));
window.fetchInTransitOrders  = page => (currentStatus='in-transit', fetchOrders(page));
window.fetchDeliveredOrders  = page => (currentStatus='completed',  fetchOrders(page));
window.fetchReturnedOrders   = page => (currentStatus='returned',   fetchOrders(page));
window.fetchRefundedOrders   = page => (currentStatus='refunded',   fetchOrders(page));
window.fetchCancelledOrders  = page => (currentStatus='cancelled',  fetchOrders(page));

// show tracking modal
window.openTracking = (orderId, existing='') => {
  $('#trackOrderId').val(orderId);
  $('#trackingCode').val(existing);
  new bootstrap.Modal($('#trackModal')).show();
};

// submit tracking
$('#trackForm').on('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch(`${BASE_URL}/assets/cPhp/update_order.php`, { method:'POST', body:fd })
    .then(r => r.json())
    .then(json => {
      if (json.error) throw new Error(json.error);
      bootstrap.Modal.getInstance($('#trackModal')).hide();
      fetchOrders(currentPage);
    })
    .catch(err => alert('Failed to save tracking: ' + err.message));
});

// show comment modal
window.openComment = orderId => {
  $('#commentOrderId').val(orderId);
  $('#commentText, #commentFile').val('');
  new bootstrap.Modal($('#commentModal')).show();
};

// submit comment
$('#commentForm').on('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch(`${BASE_URL}/assets/cPhp/add_order_comment.php`, { method:'POST', body:fd })
    .then(r => r.json())
    .then(json => {
      if (!json.success) throw new Error(json.error||'Unknown');
      bootstrap.Modal.getInstance($('#commentModal')).hide();
      fetchOrders(currentPage);
    })
    .catch(err => alert('Failed to add comment: ' + err.message));
});

// when DOM ready
$(function(){
  $('#statusFilter').on('change', function(){
    currentStatus = this.value;
    fetchOrders(1);
  });
  fetchOrders(1);
});

// export for testing
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { renderOrders };
}
