<?php
// portal/new-product-requests.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
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
    <script>
      // Must appear before any other JS
      window.BASE_URL = "<?php echo rtrim(PROJECT_BASE_URL, '/'); ?>";
    </script>
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
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">New Product Requests</h3>
            <div>
              <button id="newProductBtn" class="btn btn-primary btn-sm me-2">New Product</button>
              <button id="priceChangeBtn" class="btn btn-secondary btn-sm">Price Change</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="requestsTable">
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
                  <thead>
                    <tr><th>Min Qty</th><th>Max Qty</th><th>Unit Price</th><th></th></tr>
                  </thead>
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
      </section>
      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cJs/new-product-requests.js"></script>
  </body>
</html>
