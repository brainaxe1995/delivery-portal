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

    if (data.chart_data && document.getElementById('chartWeek')) {
      new Chart(document.getElementById('chartWeek'), {
        type: 'line',
        data: {
          labels: data.chart_data.labels,
          datasets: [
            {
              label: 'Revenue',
              data: data.chart_data.revenue,
              borderColor: '#4caf50',
              backgroundColor: 'rgba(76, 175, 80, 0.2)',
              yAxisID: 'y'
            },
            {
              label: 'Orders',
              data: data.chart_data.orders,
              borderColor: '#2196f3',
              backgroundColor: 'rgba(33, 150, 243, 0.2)',
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true, position: 'left' },
            y1: {
              beginAtZero: true,
              position: 'right',
              grid: { drawOnChartArea: false }
            }
          }
        }
      });
    }


    $.getJSON(`${BASE_URL}/assets/cPhp/get_analytics.php?period=monthly`, month => {
      if (month.chart1 && document.getElementById('chartMonth')) {
        new Chart(document.getElementById('chartMonth'), {
          type: 'bar',
          data: {
            labels: month.chart1.labels,
            datasets: [{
              label: 'Revenue',
              data: month.chart1.revenue,
              backgroundColor: '#42a5f5'
            }]
          },
          options: { responsive: true }
        });
      }
    });
  });
});
