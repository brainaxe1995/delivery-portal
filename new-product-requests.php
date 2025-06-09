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
            <table class="table table-bordered" id="requestsTable">
              <thead>
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

          <h5 class="mt-4 mb-2">Bulk Pricing</h5>
          <input type="hidden" id="priceProductId" />
          <div class="table-responsive mb-2">
            <table class="table" id="tiersTable">
              <thead>
                <tr>
                  <th>Min Qty</th>
                  <th>Max Qty</th>
                  <th>Unit Price</th>
                  <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <button id="addTier" type="button" class="btn btn-sm btn-secondary me-2">Add Tier</button>
          <button id="saveTiers" type="button" class="btn btn-sm btn-primary">Save Tiers</button>

          <!-- Tier Modal -->
          <div class="modal fade" id="tierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Bulk Pricing Tier</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <form id="tierForm">
                    <div class="mb-3">
                      <label class="form-label">Min Qty</label>
                      <input type="number" class="form-control" id="tierMin">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Max Qty</label>
                      <input type="number" class="form-control" id="tierMax">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Unit Price</label>
                      <input type="number" step="0.01" class="form-control" id="tierPrice">
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="saveTierModal">Save</button>
                </div>
              </div>
            </div>
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
