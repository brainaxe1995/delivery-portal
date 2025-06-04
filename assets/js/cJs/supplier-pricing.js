// portal/assets/js/cJs/supplier-pricing.js

$(function(){
  loadPrices();

  $('#priceForm').on('submit', function(e){
    e.preventDefault();
    const data = {
      supplier: $('#sp_supplier').val(),
      product: $('#sp_product').val(),
      price: $('#sp_price').val(),
      bulk_price: $('#sp_bulk').val(),
      effective_date: $('#sp_date').val()
    };
    $.ajax({
      method:'POST',
      url:`${BASE_URL}/assets/cPhp/add_supplier_price.php`,
      data:JSON.stringify(data),
      contentType:'application/json',
      success:() => { loadPrices(); this.reset(); }
    });
  });
});

function loadPrices(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_supplier_prices.php`, function(rows){
    const tbody = $('#pricesBody').empty();
    rows.forEach(r => {
      tbody.append(`<tr><td>${r.id}</td><td>${r.supplier}</td><td>${r.product}</td><td>${r.price}</td><td>${r.bulk_price||''}</td><td>${r.effective_date}</td></tr>`);
    });
  });
}
