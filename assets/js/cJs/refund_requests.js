// assets/js/cJs/refund_requests.js

let currentPage = 1,
    totalPages  = 1,
    PER_PAGE    = 20;

$(function(){
  fetchRequests(1);

  $('#statusFilter').on('change', () => fetchRequests(1));
});

function fetchRequests(page=1){
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_refund_requests.php`,
    method: 'GET',
    dataType: 'json',
    success(list){
      renderTable(list);
      buildPagination();
      updateUrl(page);
    },
    error(xhr, status, err){
      console.error('Refund fetch failed:', status, err);
    }
  });
}

function renderTable(list){
  const filter = $('#statusFilter').val();
  const $tb = $('#refundTable tbody').empty();
  list.filter(r => !filter || statusOf(r).includes(filter)).forEach(r => {
    const hist = r.status_history.map(h => `${h.status} (${h.date})`).join('<br>');
    const proof = r.proof ? `<a href="assets/uploads/${r.proof}" target="_blank">View</a>` : '—';
    $tb.append(`
      <tr>
        <td>#${r.order_id}</td>
        <td>${r.reason}</td>
        <td>${proof}</td>
        <td>${hist}</td>
      </tr>`);
  });
}

function statusOf(r){
  if(!Array.isArray(r.status_history) || r.status_history.length===0) return '';
  return r.status_history[r.status_history.length-1].status;
}

function updateUrl(page){
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.replaceState({}, '', newUrl);
}

window.fetchPendingOrders = fetchRequests; // alias for pagination
