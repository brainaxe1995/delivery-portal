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
  const $tb = $('#refundTable').empty();

  if (!list.length) {
    return $tb.append('<tr><td colspan="5" class="text-center">No refunds found.</td></tr>');
  }

  list.forEach(r => {
    const reason = r.reason || '—';
    const date   = r.date_created ? new Date(r.date_created)
                      .toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })
                      : '';
    const hist   = date ? `Refunded (${date})` : 'Refunded';

    $tb.append(`
      <tr class="refund-row" data-id="${r.id}">
        <td>#${r.parent_id}</td>
        <td>${reason}</td>
        <td><input type="file" class="form-control form-control-sm proof-upload" data-id="${r.id}"></td>
        <td>${hist}</td>
        <td><button class="btn btn-sm btn-primary add-comment" data-id="${r.id}">Add Comment</button></td>
      </tr>
      <tr class="comments-row">
        <td colspan="5">
          <div class="comments" id="comments-${r.id}"></div>
          <textarea class="form-control comment-text mt-2" rows="2" data-id="${r.id}"></textarea>
          <button class="btn btn-sm btn-success post-comment" data-id="${r.id}">Post</button>
        </td>
      </tr>`);
    loadComments(r.id);
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

function loadComments(id){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_refund_comments.php`, {refund_id:id}, list => {
    const cont = $(`#comments-${id}`).empty();
    list.forEach(c => cont.append(`<div><strong>User ${c.user_id}:</strong> ${c.comment} <span class="text-muted">${c.timestamp}</span></div>`));
  });
}

$(document).on('click', '.add-comment', function(){
  $(this).closest('tr').next('.comments-row').toggle();
});

$(document).on('click', '.post-comment', function(){
  const id = $(this).data('id');
  const text = $(`.comment-text[data-id="${id}"]`).val();
  if (!text) return;
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/add_refund_comment.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify({refund_id:id,user_id:1,comment:text})
  }).done(()=>{ loadComments(id); $(`.comment-text[data-id="${id}"]`).val(''); });
});

$(document).on('change', '.proof-upload', function(){
  const id = $(this).data('id');
  const file = this.files[0];
  const formData = new FormData();
  formData.append('refund_id', id);
  formData.append('status', 'approved');
  if (file) formData.append('proof', file);
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/update_refund_status.php`,
    method:'POST',
    data: formData,
    processData:false,
    contentType:false
  });
});
