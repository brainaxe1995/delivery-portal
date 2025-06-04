// assets/js/cJs/refund_requests.js

let currentPage = 1,
    totalPages  = 1,
    PER_PAGE    = 20;

$(function(){
  fetchRequests(1);

  $('#statusFilter').on('change', () => fetchRequests(1));
});

function fetchRequests(page = 1) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_refund_requests.php`,
    method: 'GET',
    data: { page, per_page: PER_PAGE },
    dataType: 'json',
    success(list, _status, xhr) {
      renderTable(list);
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      buildPagination();
      updateUrl(page);
    },
    error(xhr, status, err) {
      console.error('Refund fetch failed:', status, err);
    }
  });
}

function renderTable(list){
  const $tb = $('#refundTable tbody').empty();

  if (!list.length) {
    return $tb.append('<tr><td colspan="4" class="text-center">No refunds found.</td></tr>');
  }

  list.forEach(r => {
    const reason = r.reason || '—';
    const date   = r.date_created ? new Date(r.date_created)
                      .toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })
                      : '';
    const hist   = date ? `Refunded (${date})` : 'Refunded';

    $tb.append(`
      <tr>
        <td>#${r.parent_id}</td>
        <td>${reason}</td>
        <td>—</td>
        <td>${hist}</td>
      </tr>`);
  });
}

function statusOf(r){
  return 'refunded';
}

function updateUrl(page){
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.replaceState({}, '', newUrl);
}

window.fetchPendingOrders = fetchRequests; // alias for pagination
