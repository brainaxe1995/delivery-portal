// assets/js/cJs/delivered_orders.js

let currentPage = 1;
let totalPages  = 1;
const PER_PAGE  = 20;

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

$(function() {
  // grab ?page=... from URL if present
  const params = new URLSearchParams(location.search);
  const p0     = parseInt(params.get('page'), 10) || 1;
  fetchDeliveredOrders(p0);
});

function fetchDeliveredOrders(page) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_delivered_orders.php`,  // ← NEW
    method: 'GET',
    data: { page, per_page: PER_PAGE },
    dataType: 'json',
    success(orders) {
      renderTable(orders);
    },
    complete(xhr) {
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'),10) || 1;
      buildPagination();    // your pagination.js helper
      updateUrl(currentPage);
    },
    error(xhr, status, err) {
      console.error('⚠️ delivered_orders.js error:', xhr.status, xhr.responseText);
      alert('❌ Failed to load delivered orders');
    }
  });
}

function renderTable(orders) {
  const $tb = $('table tbody').empty();
  if (!orders.length) {
    return $tb.append(`
      <tr>
        <td colspan="6" class="text-center">No delivered orders found.</td>
      </tr>
    `);
  }
  orders.forEach(o => {
    // billing might be missing on some older/custom statuses
    const first = o.billing?.first_name  || '';
    const last  = o.billing?.last_name   || '';
    const email = escapeHtml(o.billing?.email       || 'No Email');
    const name  = escapeHtml((first + ' ' + last).trim() || 'No Name');
    const date  = o.date_created
      ? escapeHtml(new Date(o.date_created).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' }))
      : 'No Date';

    // items
    const items = (o.line_items || []).length
       ? o.line_items.map(i => `<p>${escapeHtml(i.quantity)}× ${escapeHtml(i.name)}</p>`).join('')
       : '<p>No Items</p>';

    // status badge
    const st = o.status;
    const badge = `<mark class="order-status status-${escapeHtml(st)}"><span>${escapeHtml(st.replace('-', ' '))}</span></mark>`;

    // any existing tracking in meta_data?
    const meta = o.meta_data || [];
    const trk  = escapeHtml(meta.find(m => /tracking/i.test(m.key))?.value || '');

    $tb.append(`
      <tr data-order-id="${escapeHtml(o.id)}">
        <td>#${escapeHtml(o.id)}<br/><small>${name}</small></td>
        <td><a href="mailto:${email}">${email}</a></td>
        <td>${date}</td>
        <td class="items-col">${items}</td>
        <td>${badge}</td>
        <td>${trk || '—'}</td>
      </tr>
    `);
  });
}

function updateUrl(page) {
  history.pushState(null, '', `${location.pathname}?page=${page}`);
}
