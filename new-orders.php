<?php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon"/>
    <title>Tharavix | New Orders</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/lineicons.css"/>
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" href="assets/css/main.css"/>

    <style>
      td { white-space: nowrap; vertical-align: top; }
      .items-col p { margin: 0; }
      /* green dropdown for New Orders */
      .card-style .dropdown-toggle {
        border: none;
        background: #21965338;
        color: #198754;
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
              <div class="col-md-6"><h2>New Orders</h2></div>
              <div class="col-md-6"></div>
            </div>
          </div>

          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Data Table</h6>
                  <div class="table-responsive">
                    <table id="new-orders-table" class="table table-striped table-hover align-middle">
                      <thead>
                        <tr>
                          <th><h6>Order</h6></th>
                          <th><h6>Email</h6></th>
                          <th><h6>Date</h6></th>
                          <th><h6>Order Items</h6></th>
                          <th><h6>Status</h6></th>
                          <th><h6>Update / Assign</h6></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- shared pagination -->
            <nav>
              <ul class="base-pagination pagination"></ul>
            </nav>
          </div>
        </div>
      </section>

      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <!-- ✅ Inject BASE_URL from server-config.php -->
<script>
  const BASE_URL = "<?php include 'assets/cPhp/server-config.php'; echo rtrim(PROJECT_BASE_URL, '/');
 ?>";
</script>

    <!-- page‑specific -->
    <script src="assets/js/cJs/new-orders.js"></script>
    <!-- generic pagination -->
    <script src="assets/js/cJs/pagination.js"></script>
  </body>
</html>
