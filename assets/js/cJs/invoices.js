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
          <td><a href="${BASE_URL}/assets/cPhp/download_invoice.php?id=${inv.id}" class="btn btn-sm btn-primary">PDF</a></td>
        </tr>`;
      });
      $('#invoiceTable tbody').html(html);
    }
  });
}
