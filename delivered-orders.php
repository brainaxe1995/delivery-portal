<?php
// portal/delivered-orders.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Delivered Orders</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="assets/css/lineicons.css"/>
  <link rel="stylesheet" href="assets/css/main.css"/>
  <style>.items-col p{margin:0;}</style>
</head>
<body>
  <div id="skeleton-loader"><div class="skeleton-block"></div></div>
  <aside class="sidebar-nav-wrapper"><script src="assets/js/cJs/sidebar.js"></script></aside>
  <div class="overlay"></div>

  <main class="main-wrapper">
    <header class="header">
      <script src="assets/js/cJs/header.js"></script>
      <script src="assets/js/cJs/menuToggle.js"></script>
    </header>

    <section class="table-components">
      <div class="container-fluid">
        <div class="title-wrapper pt-30 mb-3">
          <h2 class="page-title">Delivered Orders</h2>
        </div>
        <div class="card-style mb-30">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead>
                  <tr>
                    <th>Order</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Order Items</th>
                    <th>Status</th>
                    <th>Tracking</th>
                  </tr>
                </thead>
                <tbody><!-- rendered by JS --></tbody>
              </table>
            </div>
            <nav class="p-3">
              <ul class="base-pagination pagination"></ul>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer"><script src="assets/js/cJs/footer.js"></script></footer>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- supply BASE_URL for AJAX -->
  <script>const BASE_URL = "<?= $BASE_URL ?>";</script>
  <script src="assets/js/cJs/delivered_orders.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
