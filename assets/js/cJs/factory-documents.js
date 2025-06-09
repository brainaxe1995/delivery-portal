// portal/assets/js/cJs/factory-docs.js

$(function(){
  loadDocs();

  $('#docForm').on('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      method:'POST',
      url:`${BASE_URL}/assets/cPhp/upload_factory_doc.php`,
      data:formData,
      processData:false,
      contentType:false,
      success:() => { loadDocs(); this.reset(); }
    });
  });
});

function loadDocs(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_factory_documents.php`, function(rows){
    const tbody = $('#docsBody').empty();
    rows.forEach(r => {
      tbody.append(`<tr><td>${r.id}</td><td>${r.supplier}</td><td>${r.product}</td><td><a href="assets/uploads/${r.file_path}">view</a></td><td>${r.uploaded_at}</td></tr>`);
    });
  });
}
