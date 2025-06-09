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

    // Fetch on-hold orders separately
    $.getJSON(`${BASE_URL}/assets/cPhp/get_on_hold_count.php`, c => {
      $('#box-on-hold').text(c.count || 0);
    });

    function fetchTrackingNotifications() {
      $.getJSON(`${BASE_URL}/assets/cPhp/update_tracking.php`, res => {
        const events  = Array.isArray(res) ? res : (res.events || []);
        const delayed = Array.isArray(res) ? [] : (res.delayed || []);
        let list = [];
        if (events.length) {
          list = list.concat(events.map(e => ({
            message: `Order #${e.order_id}: ${e.event_type}`,
            link: `/shipments.php?order_id=${e.order_id}`
          })));
        }
        if (delayed.length) {
          list = list.concat(delayed.map(d => ({
            message: `Order #${d.order_id} delayed ${d.days_since_event} days`,
            link: `/shipments.php?order_id=${d.order_id}`
          })));
        }
        if (list.length) {
          renderNotifications(list.concat(data.notifications));
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

    $.getJSON(`${BASE_URL}/assets/cPhp/get_top_sellers.php?limit=10`, list => {
      renderTop(list);
    });

    $('#top-range').on('change', function() {
      const period = this.value;
      $.getJSON(`${BASE_URL}/assets/cPhp/get_top_sellers.php?limit=10&period=${period}`, renderTop);
    });
  });
});
