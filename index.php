<!-- portal/index.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>eCommerce Dashboard</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />

    <style>
      /* small tweak so selects don’t shrink */
      .select-sm { min-width: 120px; }
    </style>
  </head>
  <body>
    <div id="skeleton-loader"><div class="skeleton-block"></div></div>

    <aside class="sidebar-nav-wrapper">
      <script src="assets/js/cJs/sidebar.js"></script>
    </aside>
    <div class="overlay"></div>

    <main class="main-wrapper">
      <header class="header">
        <script src="assets/js/cJs/header.js"></script>
        <script src="assets/js/cJs/menuToggle.js"></script>
      </header>

      <section class="section">
        <div class="container-fluid">
          <!-- Title -->
          <div class="title-wrapper pt-30 mb-4">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h2 class="page-title">Dashboard Overview</h2>
              </div>
            </div>
          </div>

          <!-- KPI Row 1 -->
          <div class="row mb-4">
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon purple"><i class="lni lni-cart-full"></i></div>
                <div class="content">
                  <h6>Pending Orders</h6>
                  <h3 id="box-pending">Loading…</h3>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon info"><i class="lni lni-truck"></i></div>
                <div class="content">
                  <h6>Orders In Transit</h6>
                  <h3 id="box-in-transit">Loading…</h3>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon success"><i class="lni lni-package"></i></div>
                <div class="content">
                  <h6>Orders Delivered</h6>
                  <h3 id="box-delivered">Loading…</h3>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon danger"><i class="lni lni-reload"></i></div>
                <div class="content">
                  <h6>Refund Requests</h6>
                  <h3 id="box-refunds">Loading…</h3>
                </div>
              </div>
            </div>
          </div>

          <!-- KPI Row 2 -->
          <div class="row mb-5">
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon warning"><i class="lni lni-cart-full"></i></div>
                <div class="content">
                  <h6>Low Stock Alerts</h6>
                  <h3 id="box-low-stock">Loading…</h3>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="icon-card mb-30">
                <div class="icon success"><i class="lni lni-dollar"></i></div>
                <div class="content">
                  <h6>Revenue This Month</h6>
                  <h3 id="box-revenue">Loading…</h3>
                </div>
              </div>
            </div>
          </div>

          <!-- Top 10 Selling Products -->
          <div class="card-style mb-30">
            <div class="title d-flex justify-content-between align-items-center">
              <h6 class="text-medium mb-0">Top 10 Selling Products</h6>
              <div class="select-style-1">
                <div class="select-position select-sm">
                  <select id="top-range" class="form-select form-select-sm">
                    <option value="yearly" selected>Yearly</option>
                    <option value="monthly">Monthly</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sold</th>
                    <th>Profit</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="top-body">
                  <!-- injected by JS -->
                </tbody>
              </table>
            </div>
          </div>
          <!-- Notifications -->
          <div class="card-style mb-30">
            <h6 class="text-medium mb-25">Notifications</h6>
            <ul id="notif-list" class="list-group list-group-flush">
              <!-- injected by JS -->
            </ul>
          </div>
        </div>
      </section>

      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>

    <!-- JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/Chart.min.js"></script>
    <script src="assets/js/dynamic-pie-chart.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/fullcalendar.js"></script>
    <script src="assets/js/jvectormap.min.js"></script>
    <script src="assets/js/world-merc.js"></script>
    <script src="assets/js/polyfill.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
      const BASE_URL = "<?php include 'assets/cPhp/server-config.php'; echo rtrim(PROJECT_BASE_URL, '/'); ?>";
    </script>
    <script src="assets/js/cJs/dashboard.js"></script>
  </body>
</html>
