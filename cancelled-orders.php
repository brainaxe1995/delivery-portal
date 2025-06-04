<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Tharavix | Cancelled Orders</title>
    <!-- ========== CSS Files ========== -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/css/fullcalendar.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <!-- Inline CSS -->
    <style>
      td {
        white-space: normal;
        vertical-align: top;
      }
      .items-col p {
        margin: 0;
      }
      .card-style .dropdown-toggle {
        border: none;
        background: #ff000026;
        color: red;
      }
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
      <section class="table-components">
        <div class="container-fluid">
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="title">
                  <h2>Cancelled Orders</h2>
                </div>
              </div>
              <div class="col-md-6">
                <!-- Optional filters/actions -->
              </div>
            </div>
          </div>
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Data Table</h6>
                  <div class="table-responsive">
                    <table class="table align-middle">
                      <thead>
                        <tr>
                          <th><h6>Order</h6></th>
                          <th><h6>Email</h6></th>
                          <th><h6>Date</h6></th>
                          <th><h6>Order Items</h6></th>
                          <th><h6>Status</h6></th>
                          <th><h6>Update / Assign</h6></th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Injected table rows -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pagination Navigation -->
            <nav>
              <ul class="base-pagination pagination">
                <!-- Dynamic pagination will be built in pagination.js -->
              </ul>
            </nav>
          </div>
        </div>
      </section>
      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>
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
    <!-- Our custom JS files for handling orders & pagination -->
    <script>
      const BASE_URL = "<?php include 'assets/cPhp/server-config.php'; echo rtrim(PROJECT_BASE_URL, '/'); ?>";
    </script>
    
    <script src="assets/js/cJs/cancelled_orders.js"></script>
    <script src="assets/js/cJs/pagination.js"></script>
  </body>
</html>
