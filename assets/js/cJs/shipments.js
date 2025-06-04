// portal/assets/js/cJs/shipments.js

let currentPage = 1,
    totalPages  = 1,
    PER_PAGE    = 20;

$(function(){
  // Initial load
  fetchShipments(1);

  // Refresh button
  $('#refreshShipments').click(() => fetchShipments(1));

  // Show manifest modal
  $('#uploadManifestBtn').click(() =>
    new bootstrap.Modal($('#manifestModal')).show()
  );

  // Handle CSV upload
  $('#manifestForm').submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    $.ajax({
      url: `${BASE_URL}/assets/cPhp/upload_manifest.php`,
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json'
    })
    .done(json => {
      if (json.error) throw new Error(json.error);
      alert('✔️ Manifest imported');
      bootstrap.Modal.getInstance($('#manifestModal')[0]).hide();
      fetchShipments(1);
    })
    .fail((xhr, ts, err) => {
      console.error('Upload failed:', err, xhr.responseText);
      alert('Upload failed: ' + (xhr.responseJSON?.error || err));
    });
  });
});

/**
 * Fetch and render the shipments page
 */
function fetchShipments(page = 1) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_shipments_summary.php`,
    method: 'GET',
    data: { page, per_page: PER_PAGE },
    dataType: 'json',

    complete(xhr) {
      // Read total pages from header
      totalPages = parseInt(xhr.getResponseHeader('X-My-TotalPages'), 10) || 1;
      buildPagination();
      updateUrl(currentPage);
    },

    success(list) {
      const $tbody = $('#shipmentsTable tbody').empty();
      list.forEach(s => {
        $tbody.append(`
          <tr data-order-id="${s.order_id}">
            <td>#${s.order_id}</td>
            <td>${s.provider   || '—'}</td>
            <td>${s.tracking_no|| '—'}</td>
            <td>${s.eta        || '—'}</td>
            <td>${s.status}</td>
            <td>${s.last_update}</td>
            <td>
              <button class="btn btn-sm btn-primary save-btn">
                <i class="lni lni-checkmark-circle"></i>
              </button>
            </td>
          </tr>
        `);
      });
    },

    error(xhr, status, err) {
      console.error('📦 Load failed:', status, err, 'HTTP', xhr.status);
      console.error('Response:', xhr.responseText);
      alert(`Failed to load shipments (HTTP ${xhr.status}): ${err}`);
    }
  });
}

// Expose an alias for pagination.js to pick up
window.fetchPendingOrders = fetchShipments;

/**
 * Update the browser URL without reloading
 */
function updateUrl(page) {
  const newUrl = `${window.location.pathname}?page=${page}`;
  window.history.replaceState({}, '', newUrl);
}

/**
 * Periodically check 17track for status updates
 */
function checkTrackingUpdates() {
  $.getJSON(`${BASE_URL}/assets/cPhp/update_tracking.php`, events => {
    events.forEach(ev => {
      const $row = $(`#shipmentsTable tbody tr[data-order-id="${ev.order_id}"]`);
      if ($row.length) {
        $row.find('td').eq(4).text(ev.status);
        $row.find('td').eq(5).text(ev.timestamp || '');
      }
    });
  });
}

$(function(){
  checkTrackingUpdates();
  setInterval(checkTrackingUpdates, 300000); // every 5 minutes
});
