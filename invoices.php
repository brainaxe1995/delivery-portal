<?php
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
  <title>Tharavix | Invoices</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/lineicons.css" />
  <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
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
    <section class="table-components">
      <div class="container-fluid">
        <div class="title-wrapper pt-30 mb-3 d-flex justify-content-between align-items-center">
          <h2>Invoices</h2>
          <button id="addInvoice" class="main-btn primary-btn btn-hover btn-sm">Create Invoice</button>
        </div>
        <div class="card-style mb-30">
          <div class="table-responsive">
            <table class="table" id="invoiceTable">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Customer</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Download</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <nav class="p-3">
            <ul class="base-pagination pagination"></ul>
          </nav>
        </div>
      </div>
    </section>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Invoice</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                  <tr>
                    <th>Order Number</th>
                    <th>Tracking Code</th>
                    <th>Shipping Proof</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Country Name</th>
                    <th>Product Name</th>
                    <th>Stripe</th>
                    <th>Product Cost</th>
                    <th>Shipping Cost</th>
                    <th>Total Cost</th>
                    <th>Note</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <button type="button" class="btn btn-secondary" id="addItem">Add</button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveInvoice">Save</button>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer">
      <script src="assets/js/cJs/footer.js"></script>
    </footer>
  </main>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/cJs/invoices.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
