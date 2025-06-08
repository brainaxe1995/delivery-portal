$(function() {
  $.getJSON(`${BASE_URL}/assets/cPhp/get_dashboard_summary.php`, data => {
    if (data.late_shipment_percent !== undefined && $('#late-percent').length) {
      $('#late-percent').text(data.late_shipment_percent.toFixed(2) + '%');
      if (document.getElementById('lateShipmentChart')) {
        new Chart(document.getElementById('lateShipmentChart'), {
          type: 'doughnut',
          data: {
            labels: ['Late', 'On Time'],
            datasets: [{
              data: [data.late_shipment_percent, 100 - data.late_shipment_percent],
              backgroundColor: ['#f06292', '#4caf50']
            }]
          },
          options: {responsive: true, plugins:{legend:{display:false}}}
        });
      }
    }

    if (data.inventory_turnover_rate !== undefined && $('#turnover-rate').length) {
      $('#turnover-rate').text(data.inventory_turnover_rate.toFixed(2));
    }

    const rates = data.refund_rate_per_product || {};
    const labels = Object.keys(rates);
    const values = labels.map(k => rates[k]);
    if (document.getElementById('refundRateChart')) {
      new Chart(document.getElementById('refundRateChart'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Refund Rate (%)',
            data: values,
            backgroundColor: '#42a5f5'
          }]
        },
        options: {scales:{y:{beginAtZero:true,max:100}}}
      });
    } else if ($('#refund-rate-body').length) {
      const $b = $('#refund-rate-body').empty();
      labels.forEach((name,i) => {
        $b.append(`<tr><td>${name}</td><td>${values[i].toFixed(2)}%</td></tr>`);
      });
    }
  });
});
