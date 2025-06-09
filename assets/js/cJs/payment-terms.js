// JS for Payment Terms management
$(function(){
  loadTerms();
  $('#addTerm').on('click', function(){
    $('#termId').val('');
    $('#termName').val('');
    $('#termDesc').val('');
    $('#termModal').modal('show');
  });
  $('#saveTerm').on('click', saveTerm);
});

function loadTerms(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_payment_terms.php`, function(data){
    let html='';
    data.forEach(t=>{
      html += `<tr data-id="${t.id}">
        <td>${t.name}</td>
        <td>${t.description}</td>
        <td><button class="btn btn-sm btn-secondary edit-term">Edit</button></td>
      </tr>`;
    });
    $('#termsTable tbody').html(html);
    $('.edit-term').on('click', function(){
      const tr=$(this).closest('tr');
      $('#termId').val(tr.data('id'));
      $('#termName').val(tr.find('td:nth-child(1)').text());
      $('#termDesc').val(tr.find('td:nth-child(2)').text());
      $('#termModal').modal('show');
    });
  });
}

function saveTerm(){
  const payload={
    id: $('#termId').val(),
    name: $('#termName').val(),
    description: $('#termDesc').val()
  };
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/update_payment_term.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify(payload),
    success: function(){
      $('#termModal').modal('hide');
      loadTerms();
    }
  });
}
