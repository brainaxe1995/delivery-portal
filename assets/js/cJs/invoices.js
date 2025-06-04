// page-specific fetcher for Invoices
let currentPage = 1;
let totalPages  = 1;

$(function(){
  fetchInvoices(1);
  $('#addInvoice').on('click', openAddInvoice);
  $('#saveInvoice').on('click', saveInvoice);
  $('#invoiceTable').on('click', '.delete-invoice', function(){
    const id = $(this).data('id');
    deleteInvoice(id);
  });
});

function fetchInvoices(page=1){
  currentPage = page;
  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_invoices.php`,
    method: 'GET',
    data: {page, per_page:20},
    dataType: 'json',
    complete(xhr){
      const tp = parseInt(xhr.getResponseHeader('X-My-TotalPages')) || 1;
      totalPages = tp;
      buildPagination();
    },
    success(list){
      let html = '';
      list.forEach(inv => {
        html += `<tr>
          <td>${inv.id}</td>
          <td>${inv.customer}</td>
          <td>$${inv.amount}</td>
          <td>${inv.status}</td>
          <td>${inv.date}</td>
          <td><a href="${BASE_URL}/assets/cPhp/download_invoice.php?id=${inv.id}" data-id="${inv.id}" class="btn btn-sm btn-primary invoice-download">PDF</a></td>
          <td><button class="btn btn-sm btn-danger delete-invoice" data-id="${inv.id}">Delete</button></td>
        </tr>`;
      });
      $('#invoiceTable tbody').html(html);
    }
  });
}

// handle download button clicks to surface 404 errors
$(document).on('click', '.invoice-download', function(e) {
  e.preventDefault();
  const url = $(this).attr('href');
  const id  = $(this).data('id');
  fetch(url)
    .then(r => {
      if (!r.ok) throw new Error('Invoice not found');
      return r.blob();
    })
    .then(blob => {
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = `invoice-${id}.pdf`;
      document.body.appendChild(a);
      a.click();
      a.remove();
    })
    .catch(err => alert(err.message));
});

function openAddInvoice(){
  $('#invCustomer').val('');
  $('#invAmount').val('');
  $('#invStatus').val('');
  $('#invDate').val('');
  new bootstrap.Modal($('#invoiceModal')).show();
}

function saveInvoice(){
  const payload = {
    customer: $('#invCustomer').val(),
    amount: parseFloat($('#invAmount').val()||0),
    status: $('#invStatus').val(),
    date: $('#invDate').val()
  };
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/create_invoice.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify(payload)
  }).done(() => {
    bootstrap.Modal.getInstance($('#invoiceModal')[0]).hide();
    fetchInvoices(1);
  }).fail(xhr => {
    alert('Failed to create invoice: ' + (xhr.responseJSON?.error || xhr.statusText));
  });
}

function deleteInvoice(id){
  if(!confirm('Delete invoice '+id+'?')) return;
  $.ajax({
    url:`${BASE_URL}/assets/cPhp/delete_invoice.php`,
    method:'POST',
    contentType:'application/json',
    data: JSON.stringify({id})
  }).done(() => {
    const page = currentPage;
    fetchInvoices(page);
  }).fail(xhr => {
    alert('Delete failed: ' + (xhr.responseJSON?.error || xhr.statusText));
  });
}

// expose for pagination.js
window.fetchPendingOrders = fetchInvoices;
