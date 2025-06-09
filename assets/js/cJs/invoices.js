// page-specific fetcher for Invoices
let currentPage = 1;
let totalPages  = 1;
let invoiceItems = [];

$(function(){
  fetchInvoices(1);
  $('#addInvoice').on('click', openAddInvoice);
  $('#saveInvoice').on('click', saveInvoice);
  $('#addItem').on('click', addItem);
  $('#itemsTable').on('click', '.remove-item', function(){
    const idx = $(this).data('index');
    removeItem(idx);
  });
  $('#itemsTable').on('input', 'input', function(){
    const idx = $(this).closest('tr').data('index');
    const field = $(this).data('field');
    if(invoiceItems[idx]) invoiceItems[idx][field] = $(this).val();
  });
  $('#invoiceTable').on('click', '.delete-invoice', function(){
    const id = $(this).data('id');
    deleteInvoice(id);
  });
  $('#invoiceTable').on('click', '.view-invoice', function(){
    const id = $(this).data('id');
    viewInvoice(id);
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
          <td><button class="btn btn-sm btn-info view-invoice" data-id="${inv.id}">View</button></td>
          <td><button class="btn btn-sm btn-danger delete-invoice" data-id="${inv.id}">Delete</button></td>
        </tr>`;
      });
      $('#invoiceTable tbody').html(html);
    }
  });
}

// handle download button clicks
$(document).on('click', '.invoice-download', function(e){
  e.preventDefault();
  const id = $(this).data('id');
  generateInvoicePdf(id);
});

function openAddInvoice(){
  invoiceItems = [];
  addItem();
  new bootstrap.Modal($('#invoiceModal')).show();
}

function addItem(){
  invoiceItems.push({
    orderNumber: '',
    trackingCode: '',
    shippingProof: '',
    customerName: '',
    address: '',
    countryName: '',
    productName: '',
    stripe: '',
    productCost: '',
    shippingCost: '',
    totalCost: '',
    note: ''
  });
  renderItems();
}

function removeItem(idx){
  invoiceItems.splice(idx,1);
  renderItems();
}

function renderItems(){
  let html = '';
  invoiceItems.forEach((item,i)=>{
    html += `<tr data-index="${i}">
      <td><input data-field="orderNumber" type="text" class="form-control" value="${item.orderNumber}"></td>
      <td><input data-field="trackingCode" type="text" class="form-control" value="${item.trackingCode}"></td>
      <td><input data-field="shippingProof" type="text" class="form-control" value="${item.shippingProof}"></td>
      <td><input data-field="customerName" type="text" class="form-control" value="${item.customerName}"></td>
      <td><input data-field="address" type="text" class="form-control" value="${item.address}"></td>
      <td><input data-field="countryName" type="text" class="form-control" value="${item.countryName}"></td>
      <td><input data-field="productName" type="text" class="form-control" value="${item.productName}"></td>
      <td><input data-field="stripe" type="text" class="form-control" value="${item.stripe}"></td>
      <td><input data-field="productCost" type="number" step="0.01" class="form-control" value="${item.productCost}"></td>
      <td><input data-field="shippingCost" type="number" step="0.01" class="form-control" value="${item.shippingCost}"></td>
      <td><input data-field="totalCost" type="number" step="0.01" class="form-control" value="${item.totalCost}"></td>
      <td><input data-field="note" type="text" class="form-control" value="${item.note}"></td>
      <td><button type="button" class="btn btn-sm btn-danger remove-item" data-index="${i}">Remove</button></td>
    </tr>`;
  });
  $('#itemsTable tbody').html(html);
}

function saveInvoice(){
  const payload = { items: invoiceItems };
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

function viewInvoice(id){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_invoices.php?id=${id}` , inv => {
    if(!inv){
      alert('Invoice not found');
      return;
    }
    const items = Array.isArray(inv.items) ? inv.items : [];
    let html = '';
    if(items.length){
      items.forEach(it => {
        html += `<tr>
          <td>${it.orderNumber || ''}</td>
          <td>${it.trackingCode || ''}</td>
          <td>${it.shippingProof || ''}</td>
          <td>${it.customerName || ''}</td>
          <td>${it.address || ''}</td>
          <td>${it.countryName || ''}</td>
          <td>${it.productName || ''}</td>
          <td>${it.stripe || ''}</td>
          <td>${it.productCost || ''}</td>
          <td>${it.shippingCost || ''}</td>
          <td>${it.totalCost || ''}</td>
          <td>${it.note || ''}</td>
        </tr>`;
      });
    } else {
      html = '<tr><td colspan="12" class="text-center">No items</td></tr>';
    }
    $('#viewItemsTable tbody').html(html);
    $('#viewInvoiceModal .modal-title').text('Invoice #' + inv.id);
    new bootstrap.Modal($('#viewInvoiceModal')).show();
  }).fail(xhr => {
    alert('Failed to fetch invoice: ' + (xhr.responseJSON?.error || xhr.statusText));
  });
}

function generateInvoicePdf(id){
  if(typeof html2pdf === "undefined"){
    window.location = `${BASE_URL}/assets/cPhp/download_invoice.php?id=${id}`;
    return;
  }
  $.getJSON(`${BASE_URL}/assets/cPhp/get_invoices.php?id=${id}` , inv => {
    if(!inv){
      alert("Invoice not found");
      return;
    }
    const items = Array.isArray(inv.items) ? inv.items : [];
    let rows = "";
    if(items.length){
      items.forEach(it => {
        rows += `<tr><td>${it.orderNumber || ''}</td><td>${it.productName || ''}</td><td class="amount">${it.totalCost || ''}</td></tr>`;
      });
    } else {
      rows = '<tr><td colspan="3" class="text-center">No items</td></tr>';
    }
    const wrapper = $(`<div style=\"display:none\"></div>`);
    wrapper.html(`\n  <h1>Invoice #${inv.id}</h1>\n  <p><strong>Customer:</strong> ${inv.customer || ''}</p>\n  <p><strong>Date:</strong> ${inv.date || ''}</p>\n  <table style=\"width:100%;border-collapse:collapse\" border=\"1\" cellpadding=\"4\"><thead><tr><th>Order</th><th>Product</th><th>Total</th></tr></thead><tbody>${rows}</tbody><tfoot><tr><td colspan=\"2\" class=\"amount\"><strong>Total</strong></td><td class=\"amount\">${inv.amount}</td></tr></tfoot></table>`);
    $('body').append(wrapper);
    html2pdf().set({filename:`invoice-${id}.pdf`}).from(wrapper[0]).save().then(() => wrapper.remove()).catch(() => {
      wrapper.remove();
      window.location = `${BASE_URL}/assets/cPhp/download_invoice.php?id=${id}`;
    });
  }).fail(xhr => {
    alert('Failed to fetch invoice: ' + (xhr.responseJSON?.error || xhr.statusText));
  });
}

window.generateInvoicePdf = generateInvoicePdf;
// expose for pagination.js
window.fetchPendingOrders = fetchInvoices;

