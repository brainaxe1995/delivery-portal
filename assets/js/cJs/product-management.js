// portal/assets/js/cJs/product-management.js

let currentPage = 1,
    totalPages  = 1,
    allProducts = [];

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
        `<tr><td colspan="7" class="text-center">Error loading products.</td></tr>`
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
      `<tr><td colspan="7" class="text-center">No products found.</td></tr>`
    );
  }

  filtered.forEach(p => {
    $tb.append(`
      <tr>
        <td>${p.id}</td>
        <td><img src="${p.images?.[0]?.src || ''}" width="50"/></td>
        <td>${p.name}</td>
        <td>${p.stock_quantity ?? 'N/A'}</td>
        <td>${p.price}</td>
        <td>
          <span class="badge ${p.stock_status === 'instock' ? 'bg-success' : 'bg-danger'}">
            ${p.stock_status}
          </span>
        </td>
        <td>
          <button class="btn btn-sm btn-primary edit-btn" data-id="${p.id}">
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

// — Modal logic stays unchanged —
$(document).on('click', '.edit-btn', function() {
  /* …open + populate modal… */
});
$('#editProductForm').submit(function(e){ /* …save product…*/ });
