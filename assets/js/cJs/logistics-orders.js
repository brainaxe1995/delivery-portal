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

  $('#refreshShipments').on('click', function(){
    $.getJSON(`${BASE_URL}/assets/cPhp/update_tracking.php`, () => fetchLogisticsOrders(currentPage));
  });
});

// Fetch orders currently in the "processing" state
function fetchLogisticsOrders(page=1){
  currentPage = page;
  const params = { page, per_page: PER_PAGE };
  if (searchOrderId) params.order_id = searchOrderId;
  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_shipments_summary.php`,
    method: 'GET',
    data: params,
    dataType: 'json',
    success(list, textStatus, xhr){
      const $tb = $('table tbody').empty();
      list.forEach(o => {
        $tb.append(`
          <tr>
            <td>#${o.order_id}</td>
            <td>AED ${o.total}</td>
            <td>${o.status}</td>
            <td><button class="btn btn-sm btn-primary view-btn" data-id="${o.order_id}"><i class="lni lni-eye"></i></button></td>
            <td>${o.tracking_no || ''}</td>
            <td>${o.origin || ''}</td>
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

$(document).on('click','.view-btn',function(){
  const id = $(this).data('id');
  $('#shipmentDetailBody').text('Loading...');
  $.getJSON(`${BASE_URL}/assets/cPhp/get_shipment_detail.php`,{id})
    .done(data=>{
      const html = '<pre>'+JSON.stringify(data,null,2)+'</pre>';
      $('#shipmentDetailBody').html(html);
      $('#shipmentModal').modal('show');
    })
    .fail(()=>{
      $('#shipmentDetailBody').text('Failed to load shipment');
      $('#shipmentModal').modal('show');
    });
});
