// page-specific fetcher for Invoices
let currentPage = 1;
let totalPages  = 1;

$(function(){
  fetchInvoices(1);
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
