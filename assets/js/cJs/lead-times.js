// portal/assets/js/cJs/lead-times.js

$(function(){
  loadTimes();

  $('#leadForm').on('submit', function(e){
    e.preventDefault();
    const data = {
      product: $('#lt_product').val(),
      supplier: $('#lt_supplier').val(),
      lead_time: $('#lt_time').val()
    };
    $.ajax({
      method:'POST',
      url:`${BASE_URL}/assets/cPhp/add_lead_time.php`,
      data:JSON.stringify(data),
      contentType:'application/json',
      success:() => { loadTimes(); this.reset(); }
    });
  });
});

function loadTimes(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_lead_times.php`, function(rows){
    const tbody = $('#timesBody').empty();
    rows.forEach(r => {
      tbody.append(`<tr><td>${r.id}</td><td>${r.product}</td><td>${r.supplier}</td><td>${r.lead_time}</td><td>${r.last_updated}</td></tr>`);
    });
  });
}
