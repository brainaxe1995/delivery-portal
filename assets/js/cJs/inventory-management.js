// portal/assets/js/cJs/inventory-management.js

let currentPage = 1;
let totalPages  = 1;
let allItems    = [];

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

$(function() {
  // 1) On load, read ?page= or default to 1
  const params = new URLSearchParams(window.location.search);
  const p = parseInt(params.get('page'), 10) || 1;
  fetchInventory(p);

  // 2) Wire up the Refresh button & filters
  $('#refreshInventory').click(() => fetchInventory(1));
  $('#invSearch').on('input', renderTable);
  $('#invStatus').on('change', renderTable);
});

function fetchInventory(page = 1) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_inventory.php`,
    method: 'GET',
    data: { page, per_page: 20 },
    dataType: 'json',

    complete(xhr) {
      // 3) Read total pages for pagination
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages') || '1', 10);

      // 4) Rebuild the pager
      buildPagination();

      // 5) Update the URL without reload
      const newUrl = `${window.location.pathname}?page=${currentPage}`;
      window.history.replaceState({}, '', newUrl);
    },

    success(data) {
      allItems = data;
      renderTable();
    },

    error(_, __, err) {
      console.error('Inventory fetch failed:', err);
      $('#inventory-table tbody').html(
      `<tr><td colspan="4" class="text-center">Error loading inventory.</td></tr>`
      );
    }
  });
}

function renderTable() {
  const q  = $('#invSearch').val().toLowerCase();
  const st = $('#invStatus').val();

  const filtered = allItems.filter(i => {
    const matchQ = i.name.toLowerCase().includes(q) || String(i.id).includes(q);
    const matchSt = !st || i.stock_status === st;
    return matchQ && matchSt;
  });

  const $tb = $('#inventory-table tbody').empty();
  if (!filtered.length) {
    return $tb.append(
      `<tr><td colspan="7" class="text-center">No items found.</td></tr>`
    );
  }

  filtered.forEach(i => {
    const low = i.low_stock ? 'table-warning' : '';
    $tb.append(`
      <tr class="${low}">
        <td>${escapeHtml(i.id)}</td>
        <td>${escapeHtml(i.name)}</td>
        <td>${escapeHtml(i.stock_quantity ?? 'N/A')}</td>
        <td>${escapeHtml(i.safety_stock ?? '')}</td>
        <td>${escapeHtml(i.reorder_threshold ?? '')}</td>
        <td>
          <span class="badge ${i.stock_status === 'instock' ? 'bg-success' : 'bg-danger'}">${escapeHtml(i.stock_status)}</span>
        </td>
        <td>
          <button class="btn btn-sm btn-secondary edit-threshold" data-id="${i.id}">Edit</button>
          <button class="btn btn-sm btn-info view-history" data-id="${i.id}">History</button>
        </td>
      </tr>
    `);
  });
}

// --------------------------------------------------------------------------------
//     THIS LINE MAKES pagination.js “see” your fetchInventory() call
// --------------------------------------------------------------------------------
window.fetchPendingOrders = fetchInventory;

$(document).on('click', '.edit-threshold', function(){
  const id = $(this).data('id');
  const item = allItems.find(i => i.id == id) || {};
  $('#invProductId').val(id);
  $('#safetyStock').val(item.safety_stock || '');
  $('#reorderThreshold').val(item.reorder_threshold || '');
  $('#thresholdModal').modal('show');
});

$('#saveThresholds').on('click', function(){
  const product_id = $('#invProductId').val();
  const safety_stock = $('#safetyStock').val();
  const reorder_threshold = $('#reorderThreshold').val();
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/update_inventory_settings.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify({product_id, safety_stock, reorder_threshold})
  }).done(()=>{ $('#thresholdModal').modal('hide'); fetchInventory(currentPage); });
});

$(document).on('click', '.view-history', function(){
  const id = $(this).data('id');
  $.getJSON(`${BASE_URL}/assets/cPhp/get_stock_log.php`, {product_id:id}, rows => {
    const tb = $('#historyTable tbody').empty();
    rows.forEach(r => tb.append(`<tr><td>${r.change_qty}</td><td>${r.reason}</td><td>${r.timestamp}</td></tr>`));
    $('#historyModal').modal('show');
  });
});
