// portal/assets/js/cJs/lead-times.js

$(function(){
  loadTimes();

  $('#leadForm').on('submit', function(e){
    e.preventDefault();
    const data = {
      id: $('#lt_id').val() || 0,
      product: $('#lt_product').val(),
      supplier: $('#lt_supplier').val(),
      lead_time: $('#lt_time').val()
    };
    $.ajax({
      method:'POST',
      url:`${BASE_URL}/assets/cPhp/add_lead_time.php`,
      data:JSON.stringify(data),
      contentType:'application/json',
      success:() => { loadTimes(); $('#leadForm')[0].reset(); $('#lt_id').val(''); }
    });
  });
});

function loadTimes(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_lead_times.php`, function(rows){
    const tbody = $('#timesBody').empty();
    rows.forEach(r => {
      tbody.append(`<tr data-id="${r.id}"><td>${r.id}</td><td>${r.product}</td><td>${r.supplier}</td><td>${r.lead_time}</td><td>${r.last_updated}</td><td><button class="btn btn-sm btn-secondary edit-time">Edit</button></td></tr>`);
    });
  });
}

$(document).on('click','.edit-time', function(){
  const row = $(this).closest('tr');
  $('#lt_id').val(row.data('id'));
  $('#lt_product').val(row.children().eq(1).text());
  $('#lt_supplier').val(row.children().eq(2).text());
  $('#lt_time').val(row.children().eq(3).text());
});
