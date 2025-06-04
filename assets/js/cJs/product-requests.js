// portal/assets/js/cJs/product-requests.js

$(function(){
  loadRequests();

  $('#requestForm').on('submit', function(e){
    e.preventDefault();
    const data = {
      supplier: $('#supplier').val(),
      product: $('#product').val(),
      description: $('#description').val()
    };
    $.ajax({
      method:'POST',
      url:`${BASE_URL}/assets/cPhp/add_product_request.php`,
      data:JSON.stringify(data),
      contentType:'application/json',
      success:() => { loadRequests(); this.reset(); }
    });
  });
});

function loadRequests(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_product_requests.php`, function(rows){
    const tbody = $('#requestsBody').empty();
    rows.forEach(r => {
      tbody.append(`<tr><td>${r.id}</td><td>${r.supplier}</td><td>${r.product}</td><td>${r.description||''}</td><td>${r.requested_at}</td></tr>`);
    });
  });
}
