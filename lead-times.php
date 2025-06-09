<?php
// portal/lead-times.php
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Sourcing & Pricing | Lead Times</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <script>const BASE_URL = "<?= $BASE_URL ?>";</script>
  </head>
  <body>
    <div id="skeleton-loader"><div class="skeleton-block"></div></div>
    <aside class="sidebar-nav-wrapper"><script src="assets/js/cJs/sidebar.js"></script></aside>
    <div class="overlay"></div>
    <main class="main-wrapper">
      <header class="header"><script src="assets/js/cJs/header.js"></script><script src="assets/js/cJs/menuToggle.js"></script></header>
      <section class="section">
        <div class="container-fluid">
          <h3 class="mb-3">Lead Time Tracking</h3>
          <form id="leadForm" class="row g-2 mb-3">
            <div class="col-md-3"><input id="lt_product" class="form-control" placeholder="Product" required></div>
            <div class="col-md-3"><input id="lt_supplier" class="form-control" placeholder="Supplier" required></div>
            <div class="col-md-3"><input id="lt_time" class="form-control" placeholder="Lead Time (days)" required></div>
            <div class="col-md-3"><button class="btn btn-primary w-100">Save</button></div>
          </form>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
              <thead><tr><th>ID</th><th>Product</th><th>Supplier</th><th>Lead Time</th><th>Updated</th></tr></thead>
              <tbody id="timesBody"></tbody>
            </table>
          </div>
        </div>
      </section>
      <footer class="footer"><script src="assets/js/cJs/footer.js"></script></footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cJs/lead-times.js"></script>
  </body>
</html>
