// assets/js/cJs/logistics_orders.js
// Fetch logistics/shipment orders summary and populate table with pagination

let currentPage   = 1;
let totalPages    = 1;
const PER_PAGE    = 20;
let searchOrderId = '';

$(function(){
  const params  = new URLSearchParams(location.search);
  const p       = parseInt(params.get('page'), 10) || 1;
  searchOrderId = params.get('order_id') || '';

  $('#orderSearch').val(searchOrderId);

  fetchLogisticsOrders(p);

  $('#orderSearch').on('change', function(){
    searchOrderId = this.value.trim();
    fetchLogisticsOrders(1);
  });
});

// Fetch orders currently in the "processing" state
function fetchLogisticsOrders(page=1){
  currentPage = page;
  const params = { page, per_page: PER_PAGE };
  if (searchOrderId) params.order_id = searchOrderId;
  $.ajax({
    url: 'assets/cPhp/get_shipments_summary.php',
    method: 'GET',
    data: params,
    dataType: 'json',
    success(list, textStatus, xhr){
      const $tb = $('table tbody').empty();
      list.forEach(o => {
        $tb.append(`
          <tr>
            <td>#${o.order_id}</td>
            <td>${formatDate(o.last_update)}</td>
            <td>${o.status}</td>
            <td>-</td>
            <td>-</td>
            <td><button class="btn btn-sm btn-primary view-btn" data-id="${o.order_id}"><i class="lni lni-eye"></i></button></td>
            <td>${o.tracking_no || ''}</td>
            <td>${o.provider || ''}</td>
          </tr>
        `);
      });

      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      buildPagination();
      updateUrl(page);
    },
    error(xhr, status, err){
      console.error('Failed to fetch logistics orders:', err);
      alert('Failed to load logistics orders');
    }
  });
}

// expose for pagination.js
window.fetchPendingOrders = fetchLogisticsOrders;

function updateUrl(page){
  let newUrl = `${window.location.pathname}?page=${page}`;
  if (searchOrderId) newUrl += `&order_id=${encodeURIComponent(searchOrderId)}`;
  window.history.replaceState({}, '', newUrl);
}

function formatDate(str){
  if(!str) return '';
  return new Date(str).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
}
