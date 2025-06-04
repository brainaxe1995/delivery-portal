let currentPage = 1;
let totalPages = 1; // will be updated from API response headers
const PER_PAGE = 20;

$(document).ready(function() {
  // On page load, get the page from URL ?page= if available
  const urlParams = new URLSearchParams(window.location.search);
  const pageParam = parseInt(urlParams.get('page')) || 1;
  fetchCancelledOrders(pageParam);
});

function fetchCancelledOrders(page) {
  currentPage = page;
  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_cancelled_orders.php?page=${page}&per_page=${PER_PAGE}`
,
    method: 'GET',
    dataType: 'json',
    success: function (orders, textStatus, xhr) {
      updateTable(orders);
      
      // Get total pages from our custom header (set in PHP)
      let tp = parseInt(xhr.getResponseHeader('X-My-TotalPages'));
      if (!isNaN(tp) && tp > 0) {
        totalPages = tp;
      }
      buildPagination();
      updateUrl(page);
    },
    error: function (xhr, textStatus, errorThrown) {
      console.error('Error fetching cancelled orders:', textStatus, errorThrown);
    }
  });
}

function updateTable(orders) {
  let rowsHtml = '';
  $.each(orders, function (i, order) {
    // Column 1: "Order" (Order ID + Name)
    let orderId = order.id ? `#${order.id}` : '#N/A';
    let firstName = (order.billing && order.billing.first_name) || '';
    let lastName  = (order.billing && order.billing.last_name) || '';
    let fullName = (firstName + ' ' + lastName).trim() || 'No Name';
    let orderDisplay = `${orderId} ${fullName}`;
    
    // Column 2: Email
    let billingEmail = (order.billing && order.billing.email) || 'No Email';
    
    // Column 3: Date (formatted like "Apr 16, 2025")
    let dateText = 'No Date';
    if (order.date_created) {
      let dateObj = new Date(order.date_created);
      let opts = { month: 'short', day: 'numeric', year: 'numeric' };
      dateText = dateObj.toLocaleDateString('en-US', opts);
    }
    
    // Column 4: Order Items (each on a new line)
    let itemsHtml = '<p>No Items</p>';
    if (order.line_items && order.line_items.length) {
      itemsHtml = order.line_items.map(item => `<p>${item.name}</p>`).join('');
    }
    
    // Column 5: Status (dropdown using Bootstrap default)
    let statusText = getStatusLabel(order.status);
    let statusClass = 'status-' + statusText.toLowerCase().replace(/\s+/g, '-');
    let dropdownHtml = `
      <div class="btn-group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="status-text ${statusClass}">${statusText}</span>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" data-status="Pending">Pending</a></li>
          <li><a class="dropdown-item" href="#" data-status="Processing">Processing</a></li>
          <li><a class="dropdown-item" href="#" data-status="In Transit">In Transit</a></li>
          <li><a class="dropdown-item" href="#" data-status="Delivered">Delivered</a></li>
          <li><a class="dropdown-item" href="#" data-status="Returned">Returned</a></li>
          <li><a class="dropdown-item" href="#" data-status="Refunded">Refunded</a></li>
          <li><a class="dropdown-item" href="#" data-status="Cancelled">Cancelled</a></li>
        </ul>
      </div>
    `;
    
    // Column 6: Update / Assign (Tracking Code form)
    let trackingCode = '';
    if (order.meta_data && order.meta_data.length > 0) {
      let trackMeta = order.meta_data.find(m => m.key === '_tracking_number');
      if (trackMeta) {
        trackingCode = trackMeta.value;
      }
    }
    
    rowsHtml += `
      <tr>
        <td class="min-width"><p>${orderDisplay}</p></td>
        <td class="min-width"><p><a href="mailto:${billingEmail}">${billingEmail}</a></p></td>
        <td class="min-width"><p>${dateText}</p></td>
        <td class="min-width items-col">${itemsHtml}</td>
        <td class="min-width">${dropdownHtml}</td>
        <td class="min-width">
          <div class="action">
            <form class="d-flex align-items-center" method="POST">
              <input type="text" class="form-control tracking-code-input" placeholder="Tracking Code" value="${trackingCode}" aria-label="Tracking Code">
              <button type="submit" class="btn btn-link p-0 ms-2 update-btn" title="Update Tracking Code">
                <i class="lni lni-checkmark-circle" style="font-size: 1.5rem; color: #28a745;"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
    `;
  });
  $('table tbody').html(rowsHtml);
}

function getStatusLabel(wooStatus) {
  if (!wooStatus) return 'Unknown';
  switch (wooStatus) {
    case 'pending':    return 'New Order';
    case 'processing': return 'Processing';
    case 'on-hold':    return 'On Hold';
    case 'cancelled':  return 'Cancelled';
    case 'refunded':   return 'Refunded';
    case 'completed':  return 'Completed';
    default:           return wooStatus;
  }
}

function updateUrl(page) {
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.pushState({ page: page }, '', newUrl);
}
