// page‑specific fetcher for “New Orders”
let currentPage = 1;
let totalPages  = 1;

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

$(function(){
  fetchNewOrders(1);
});

function fetchNewOrders(page = 1) {
  currentPage = page;

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/get_processing_orders.php`,
    method: 'GET',
    data: { page, per_page: 20 },
    dataType: 'json',

    complete(xhr) {
      const tp = parseInt(xhr.getResponseHeader('X-My-TotalPages'));
      totalPages = tp ? parseInt(tp, 10) : 1;

      // ✅ Build pagination here after we have totalPages
      buildPagination();
    },

    success(orders) {
      let html = '';
      orders.forEach(o => {
        const idText = o.id ? `#${escapeHtml(o.id)}` : '#N/A';
        const name = escapeHtml(((o.billing?.first_name || '') + ' ' + (o.billing?.last_name || '')).trim() || 'No Name');
        const email = escapeHtml(o.billing?.email || 'No Email');

        let date = 'No Date';
        if (o.date_created) {
          date = escapeHtml(new Date(o.date_created)
                   .toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
        }

        let itemsHtml = '<p>No Items</p>';
        if (Array.isArray(o.line_items) && o.line_items.length) {
          itemsHtml = o.line_items.map(i => `<p>${escapeHtml(i.name)} – Qty ${escapeHtml(i.quantity)}</p>`).join('');
        }

        const statusDd = `
          <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
              <span class="status-text">${escapeHtml(o.status)}</span>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
              <li><a class="dropdown-item" href="#" data-status="processing">Processing</a></li>
              <li><a class="dropdown-item" href="#" data-status="completed">Completed</a></li>
              <li><a class="dropdown-item" href="#" data-status="cancelled">Cancelled</a></li>
            </ul>
          </div>`;

        let tracking = '';
        if (Array.isArray(o.meta_data)) {
          o.meta_data.some(m => {
            if (/tracking_number$/i.test(m.key) && m.value) {
              tracking = m.value; return true;
            }
            if (/vi_wot/i.test(m.key) && typeof m.value === 'string') {
              try {
                const arr = JSON.parse(m.value);
                if (Array.isArray(arr) && arr[0]?.tracking_number) {
                  tracking = arr[0].tracking_number;
                  return true;
                }
              } catch {}
            }
          });
        }

        html += `
          <tr data-order-id="${escapeHtml(o.id)}">
            <td><p>${idText} ${name}</p></td>
            <td><p><a href="mailto:${email}">${email}</a></p></td>
            <td><p>${date}</p></td>
            <td class="items-col">${itemsHtml}</td>
            <td>${statusDd}</td>
            <td>
              <form class="tracking-form d-flex align-items-center">
                <input type="text" class="form-control tracking-code-input" placeholder="Tracking Code" value="${escapeHtml(tracking)}">
                <button type="submit" class="btn btn-link p-0 ms-2 update-btn" title="Update">
                  <i class="lni lni-checkmark-circle" style="font-size:1.5rem;color:#28a745"></i>
                </button>
              </form>
            </td>
          </tr>`;
      });

      $('#new-orders-table tbody').html(html);
    },

    error(_, __, err) {
      console.error('Load failed:', err);
    }
  });
}

// tracking update
$('#new-orders-table').on('submit', '.tracking-form', function (e) {
  e.preventDefault();
  const $tr = $(this).closest('tr');
  const id = $tr.data('order-id');
  const code = $(this).find('.tracking-code-input').val().trim();

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/update_order.php`,
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ order_id: id, tracking_code: code })
  })
  .done(() => alert('✅ Tracking updated'))
  .fail(xhr => { console.error(xhr.responseText); alert('❌ Update failed'); });
});

// status update
$('#new-orders-table').on('click', '.dropdown-item', function (e) {
  e.preventDefault();
  const status = $(this).data('status');
  const $tr = $(this).closest('tr');
  const id = $tr.data('order-id');

  $(this).closest('.btn-group').find('.status-text').text(status);

  $.ajax({
    url: `${BASE_URL}/assets/cPhp/update_order.php`,
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ order_id: id, status: status })
  })
  .done(() => alert(`✅ Status set to "${status}"`))
  .fail(xhr => { console.error(xhr.responseText); alert('❌ Status failed'); });
});
