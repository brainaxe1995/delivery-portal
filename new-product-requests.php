<?php
// portal/new-product-requests.php
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Sourcing & Pricing | Product Requests</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <script>const BASE_URL = "<?= $BASE_URL ?>";</script>
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
          <h3 class="mb-3">New Product Requests</h3>
          <form id="requestForm" class="row g-2 mb-3">
            <div class="col-md-3"><input id="supplier" class="form-control" placeholder="Supplier" required></div>
            <div class="col-md-3"><input id="product" class="form-control" placeholder="Product" required></div>
            <div class="col-md-4"><input id="description" class="form-control" placeholder="Description"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div>
          </form>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead><tr><th>ID</th><th>Supplier</th><th>Product</th><th>Description</th><th>Requested</th></tr></thead>
              <tbody id="requestsBody"></tbody>
            </table>
          </div>
        </div>
      </section>
      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cJs/product_requests.js"></script>
  </body>
</html>
