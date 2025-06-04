// JS for product requests page
let currentPage = 1;
let totalPages  = 1;

$(function(){
  fetchRequests(1);
  $('#newProductBtn').on('click', function(){
    $('#requestForm')[0].reset();
    $('#requestType').val('new_product');
    $('#requestModal').modal('show');
  });
  $('#priceChangeBtn').on('click', function(){
    $('#requestForm')[0].reset();
    $('#requestType').val('price_change');
    $('#requestModal').modal('show');
  });
  $('#saveRequest').on('click', submitRequest);
});

function fetchRequests(page=1){
  currentPage = page;
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/get_product_requests.php`,
    method:'GET',
    data:{page, per_page:20},
    dataType:'json',
    complete(xhr){
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages')) || 1;
      buildPagination();
    },
    success(list){
      let html='';
      list.forEach(r=>{
        html += `<tr>
          <td>${r.id}</td>
          <td>${r.type.replace('_',' ')}</td>
          <td>${r.product}</td>
          <td>$${r.proposed_price}</td>
          <td>${r.reason || ''}</td>
          <td><span class="badge ${r.status==='Approved'?'bg-success':r.status==='Rejected'?'bg-danger':'bg-secondary'}">${r.status}</span></td>
          <td>
            <button class="btn btn-sm btn-success approve-btn" data-id="${r.id}">Approve</button>
            <button class="btn btn-sm btn-danger reject-btn" data-id="${r.id}">Reject</button>
          </td>
        </tr>`;
      });
      $('#requestsTable tbody').html(html);
    }
  });
}

function submitRequest(){
  const payload = {
    type: $('#requestType').val(),
    product: $('#productName').val(),
    proposed_price: $('#proposedPrice').val(),
    reason: $('#requestReason').val()
  };
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/submit_product_request.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify(payload)
  }).done(()=>{
    $('#requestModal').modal('hide');
    fetchRequests(1);
  }).fail(xhr=>{
    alert('Submit failed: ' + (xhr.responseJSON?.error || xhr.statusText));
  });
}

$(document).on('click', '.approve-btn', function(){
  const id = $(this).data('id');
  updateStatus(id,'Approved');
});

$(document).on('click', '.reject-btn', function(){
  const id = $(this).data('id');
  updateStatus(id,'Rejected');
});

function updateStatus(id,status){
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/update_product_request.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify({id, status})
  }).done(()=> fetchRequests(currentPage))
    .fail(xhr=> alert('Update failed: ' + (xhr.responseJSON?.error || xhr.statusText)));
}

// expose fetcher for pagination
window.fetchPendingOrders = fetchRequests;
