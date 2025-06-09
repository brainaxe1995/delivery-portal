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
  <title>Tharavix | Product Requests</title>
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
          <h2>Product Requests</h2>
          <div>
            <button id="newProductBtn" class="main-btn primary-btn btn-hover btn-sm">Add Request</button>
          </div>
        </div>
        <div class="card-style mb-30">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="requestsTable">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Supplier</th>
                  <th>Product</th>
                  <th>Description</th>
                  <th>Requested</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="requestsBody"></tbody>
            </table>
          </div>
          <nav class="p-3"><ul class="base-pagination pagination"></ul></nav>
        </div>
      </div>
    </section>

    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Submit Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="requestForm">
              <div class="mb-3">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" id="supplier" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" id="product" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="description"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveRequest">Submit</button>
          </div>
        </div>
    </div>
  </div>
    <!-- Pricing Modal -->
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Bulk Pricing</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="priceProductId" />
            <table class="table table-striped table-hover" id="tiersTable">
              <thead><tr><th>Min Qty</th><th>Max Qty</th><th>Unit Price</th><th></th></tr></thead>
              <tbody></tbody>
            </table>
            <button id="addTier" type="button" class="btn btn-sm btn-secondary">Add Tier</button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveTiers">Save</button>
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
  <script src="assets/js/cJs/product_requests.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
