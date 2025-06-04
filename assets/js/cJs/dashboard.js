$(function() {
  $.getJSON(`${BASE_URL}/assets/cPhp/get_dashboard_summary.php`, data => {
    // Fill KPI cards
    $('#box-pending').text(data.pending);
    $('#box-in-transit').text(data.in_transit);
    $('#box-delivered').text(data.delivered);
    $('#box-refunds').text(data.refunded);
    $('#box-low-stock').text(data.low_stock);
    $('#box-revenue').text(`AED ${data.revenue.toFixed(2)}`);

    function renderNotifications(list) {
      const $n = $('#notif-list').empty();
      (list || []).forEach(n => {
        $n.append(`
          <li class="list-group-item">
            <a href="${n.link}">${n.message}</a>
          </li>
        `);
      });
    }

    renderNotifications(data.notifications);

    function fetchTrackingNotifications() {
      $.getJSON(`${BASE_URL}/assets/cPhp/update_tracking.php`, evs => {
        if (Array.isArray(evs) && evs.length) {
          const list = evs.map(e => ({
            message: `Order #${e.order_id}: ${e.event_type}`,
            link: `/shipments.php?order_id=${e.order_id}`
          })).concat(data.notifications);
          renderNotifications(list);
        }
      });
    }

    fetchTrackingNotifications();
    setInterval(fetchTrackingNotifications, 300000);

    function renderTop(list) {
      const $b = $('#top-body').empty();
      (list || []).slice(0,10).forEach((item, i) => {
        $b.append(`
          <tr>
            <td>${i+1}</td>
            <td>
              <div class="d-flex align-items-center">
                <img src="${item.image}" width="40" class="rounded me-2"/>
                ${item.name}
              </div>
            </td>
            <td>${item.category}</td>
            <td>AED ${item.price}</td>
            <td>${item.sold}</td>
            <td>AED ${item.profit.toFixed(2)}</td>
            <td><a href="#"><i class="lni lni-eye"></i></a></td>
          </tr>
        `);
      });
    }

    renderTop(data.top_sellers_yearly || data.top_sellers);

    $('#top-range').on('change', function() {
      const list = (this.value === 'monthly'
        ? (data.top_sellers_monthly || data.top_sellers)
        : (data.top_sellers_yearly  || data.top_sellers)
      );
      renderTop(list);
    });
  });
});
