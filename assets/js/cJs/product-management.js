// portal/assets/js/cJs/product-management.js

let currentPage = 1,
    totalPages  = 1,
    allProducts = [];

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

$(function(){
  // 1) Read ?page= or default to 1
  const params = new URLSearchParams(window.location.search);
  const p      = parseInt(params.get('page'), 10) || 1;
  fetchProducts(p);

  // 2) Wire up UI
  $('#refreshProducts').click(() => fetchProducts(1));
  $('#searchInput').on('input', renderTable);
  $('#statusFilter').on('change', renderTable);
});

function fetchProducts(page = 1) {
  currentPage = page;
  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_products.php`,
    method: 'GET',
    data: { page, per_page: 20 },
    dataType: 'json',

    complete(xhr) {
      // Grab total pages from header
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages') || '1', 10);

      // Rebuild the pager
      buildPagination();

      // Update the URL
      const newUrl = `${window.location.pathname}?page=${currentPage}`;
      window.history.replaceState({}, '', newUrl);
    },

    success(products) {
      allProducts = products;
      renderTable();
    },

    error(_, __, err) {
      console.error('📦 Fetch failed:', err);
      $('#products-table tbody').html(
        '<tr><td colspan="11" class="text-center">Error loading products.</td></tr>'
      );
    }
  });
}

function renderTable() {
  const q  = $('#searchInput').val().toLowerCase();
  const st = $('#statusFilter').val();

  const filtered = allProducts.filter(p => {
    const matchesQuery  = p.name.toLowerCase().includes(q) || String(p.id).includes(q);
    const matchesStatus = !st || p.stock_status === st;
    return matchesQuery && matchesStatus;
  });

  const $tb = $('#products-table tbody').empty();
  if (!filtered.length) {
    return $tb.append(
      '<tr><td colspan="11" class="text-center">No products found.</td></tr>'
    );
  }

  filtered.forEach(p => {
    const variants = Array.isArray(p.variant_attributes)
      ? p.variant_attributes.map(v => v.map(a => `${a.name}: ${a.option}`).join(' / ')).join('\n')
      : '';
    $tb.append(`
      <tr>
        <td>${escapeHtml(p.id)}</td>
        <td><img src="${escapeHtml(p.images?.[0]?.src || '')}" width="50"/></td>
        <td>${escapeHtml(p.name)}</td>
        <td>${escapeHtml(p.sku || '')}</td>
        <td class="variant-info">${escapeHtml(variants)}</td>
        <td>${escapeHtml(p.stock_quantity ?? 'N/A')}</td>
        <td>${escapeHtml(p.price)}</td>
        <td>${escapeHtml(p.moq ?? '')}</td>
        <td>
          <span class="badge ${
            p.stock_status === 'instock'
              ? 'bg-success'
              : p.stock_status === 'discontinued'
              ? 'bg-secondary'
              : 'bg-danger'
          }">
            ${escapeHtml(p.stock_status)}
          </span>
        </td>
        <td>${escapeHtml(p.restock_eta ?? '')}</td>
        <td>
          <button class="btn btn-sm btn-primary edit-btn" data-id="${escapeHtml(p.id)}">
            Edit
          </button>
        </td>
      </tr>
    `);
  });
}

function updateUrl(page) {
  history.replaceState({}, '', `${location.pathname}?page=${page}`);
}

// --------------------------------------------------------------------------------
// Alias fetchProducts to one of pagination.js’s known hooks.
// Here we pick “fetchPendingOrders” but you can use any of the supported names.
// --------------------------------------------------------------------------------
window.fetchPendingOrders = fetchProducts;

// — Modal logic —
$(document).on('click', '.edit-btn', function() {
  const id = $(this).data('id');
  $.getJSON(`${BASE_URL}/assets/cPhp/get_product.php`, { id })
    .done(p => {
      $('#edit-id').val(p.id);
      $('#edit-name').val(p.name);
      $('#edit-price').val(p.price || p.regular_price || '');
      const moqMeta = (p.meta_data || []).find(m => m.key === 'moq');
      $('#edit-moq').val(moqMeta ? moqMeta.value : '');
      $('#edit-stock').val(p.stock_quantity ?? '');
      $('#edit-status').val(p.stock_status || 'instock');

      $('#edit-restock').val(p.restock_eta ?? '');

      $('#edit-packaging-url').val(p.packaging_info_url || '');
      $('#edit-safety-url').val(p.safety_sheet_url || '');

      new bootstrap.Modal($('#editProductModal')).show();
    })
    .fail(xhr => {
      console.error('Product load failed:', xhr.responseText);
      alert('Failed to load product information');
    });
});

$('#editProductForm').submit(function(e){
  e.preventDefault();
  const payload = {
    id:    $('#edit-id').val(),
    price: $('#edit-price').val(),
    moq:   $('#edit-moq').val(),
    stock: $('#edit-stock').val(),
    status: $('#edit-status').val(),

    restock_eta: $('#edit-restock').val(),

    packaging_info_url: $('#edit-packaging-url').val(),
    safety_sheet_url: $('#edit-safety-url').val()

  };

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/update_product.php`,
    method: 'POST',
    contentType: 'application/json',
    dataType: 'json',
    data: JSON.stringify(payload)
  })
  .done(() => {
    bootstrap.Modal.getInstance($('#editProductModal')[0]).hide();
    fetchProducts(currentPage);
  })
  .fail(xhr => {
    console.error('Update failed:', xhr.responseText);
    alert('Failed to update product');
  });
});
