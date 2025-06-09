// JS for product requests page
let currentPage = 1;
let totalPages  = 1;

$(function(){
  fetchRequests(1);
  $('#newProductBtn,#priceChangeBtn').on('click', function(){
    $('#requestForm')[0].reset();
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
          <td>${r.supplier}</td>
          <td>${r.product}</td>
          <td>${r.description || ''}</td>
          <td>${r.requested_at}</td>
          <td><button class="btn btn-sm btn-secondary pricing-btn" data-id="${r.id}">Pricing</button></td>
        </tr>`;
      });
      const tbody = $('#requestsTable tbody,#requestsBody');
      tbody.html(html);
    }
  });
}

function submitRequest(){
  const payload = {
    supplier: $('#supplier').val() || 'N/A',
    product: $('#product').val() || $('#productName').val(),
    description: $('#description').val() || $('#requestReason').val()
  };
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/add_product_request.php`,
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

$(document).on('click', '.pricing-btn', function(){
  const id = $(this).data('id');
  $('#priceProductId').val(id);
  loadTiers(id);
  $('#pricingModal').modal('show');
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

function loadTiers(id){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_bulk_pricing.php`, {product_id:id}, tiers => {
    const tbody = $('#tiersTable tbody').empty();
    tiers.forEach(t => addTierRow(t.min_qty, t.max_qty, t.unit_price));
  });
}

function addTierRow(min='', max='', price=''){
  $('#tiersTable tbody').append(`
    <tr>
      <td><input type="number" class="form-control form-control-sm min" value="${min}"></td>
      <td><input type="number" class="form-control form-control-sm max" value="${max}"></td>
      <td><input type="number" step="0.01" class="form-control form-control-sm price" value="${price}"></td>
      <td><button type="button" class="btn btn-sm btn-danger remove-tier">&times;</button></td>
    </tr>`);
}

$('#addTier').on('click', () => addTierRow());

$(document).on('click','.remove-tier', function(){ $(this).closest('tr').remove(); });

$('#saveTiers').on('click', function(){
  const product_id = $('#priceProductId').val();
  const tiers = [];
  $('#tiersTable tbody tr').each(function(){
    tiers.push({
      min_qty: $(this).find('.min').val(),
      max_qty: $(this).find('.max').val(),
      unit_price: $(this).find('.price').val()
    });
  });
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/update_bulk_pricing.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify({product_id, tiers})
  }).done(()=>$('#pricingModal').modal('hide'))
    .fail(xhr=>alert('Save failed: ' + (xhr.responseJSON?.error || xhr.statusText)));
});
