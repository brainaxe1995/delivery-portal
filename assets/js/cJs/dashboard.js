$(function() {
  $.getJSON(`${BASE_URL}/assets/cPhp/get_dashboard_summary.php`, data => {
    // Fill KPI cards
    $('#box-pending').text(data.pending);
    $('#box-in-transit').text(data.in_transit);
    $('#box-delivered').text(data.delivered);
    $('#box-refunds').text(data.refunded);
    $('#box-low-stock').text(data.low_stock);
    $('#box-revenue').text(`AED ${data.revenue.toFixed(2)}`);

    // Function to render Top 10 list
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

    // Initial load (yearly)
    renderTop(data.top_sellers_yearly || data.top_sellers);

    // Toggle Yearly/Monthly
    $('#top-range').on('change', function() {
      const list = (this.value === 'monthly'
        ? (data.top_sellers_monthly || data.top_sellers)
        : (data.top_sellers_yearly  || data.top_sellers)
      );
      renderTop(list);
    });
  });
});
