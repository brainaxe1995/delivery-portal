// assets/js/cJs/analytics.js

$(function(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_dashboard_summary.php`, data => {
    const week = data.chart_data || {labels:[], revenue:[], profit:[], orders:[]};
    const month = data.chart1 || {labels:[], revenue:[]};

    const ctxWeek = document.getElementById('chartWeek');
    if (ctxWeek) {
      new Chart(ctxWeek, {
        type: 'line',
        data: {
          labels: week.labels,
          datasets: [
            {
              label: 'Revenue',
              data: week.revenue,
              borderColor: '#4F46E5',
              backgroundColor: 'rgba(99,102,241,0.3)',
              tension: 0.3,
            },
            {
              label: 'Profit',
              data: week.profit,
              borderColor: '#059669',
              backgroundColor: 'rgba(16,185,129,0.3)',
              tension: 0.3,
            },
            {
              label: 'Orders',
              data: week.orders,
              borderColor: '#F59E0B',
              backgroundColor: 'rgba(245,158,11,0.3)',
              tension: 0.3,
            }
          ]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });
    }

    const ctxMonth = document.getElementById('chartMonth');
    if (ctxMonth) {
      new Chart(ctxMonth, {
        type: 'bar',
        data: {
          labels: month.labels,
          datasets: [{
            label: 'Revenue',
            data: month.revenue,
            backgroundColor: '#4F46E5'
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });
    }
  });
});
