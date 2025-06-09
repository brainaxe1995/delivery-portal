<?php
// portal/product-management.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon"/>
    <title>Tharavix | Product Management</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/lineicons.css"/>
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.css"/>
    <link rel="stylesheet" href="assets/css/main.css"/>

    <style>
      td { white-space: nowrap; vertical-align: top; }
      td.variant-info { white-space: pre-line; }
      img { border-radius: 4px; }
      .badge { font-size: .9em; }
    </style>
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

      <section class="table-components">
        <div class="container-fluid">
          <!-- Title/Refresh -->
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6"><h2 class="page-title">Product Management</h2></div>
              <div class="col-md-6 text-end">
                <button id="refreshProducts" class="main-btn primary-btn btn-hover btn-sm">
                  <i class="lni lni-reload me-1"></i> Refresh
                </button>
              </div>
            </div>
          </div>

          <!-- Card -->
          <div class="card-style mb-30">
            <div class="card-body">
              <!-- Filters -->
              <div class="filter-bar d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <label for="searchInput" class="me-2 mb-0">Search:</label>
                  <input id="searchInput" type="text"
                         class="form-control form-control-sm"
                         placeholder="Name or ID..." style="width:200px;"/>
                </div>
                <div class="d-flex align-items-center">
                  <label for="statusFilter" class="me-2 mb-0">Status:</label>
                  <select id="statusFilter" class="form-select form-select-sm" style="width:150px;">
                    <option value="">All</option>
                    <option value="instock">In Stock</option>
                    <option value="outofstock">Out of Stock</option>
                    <option value="discontinued">Discontinued</option>
                  </select>
                </div>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table id="products-table" class="table table-striped table-hover align-middle mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Name</th>
                      <th>SKU</th>
                      <th>Variants</th>
                      <th>Stock</th>
                      <th>Price</th>
                      <th>MOQ</th>
                      <th>Status</th>
                      <th>Restock ETA</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody><!-- injected by JS --></tbody>
                </table>
              </div>

              <!-- Pagination -->
              <nav class="mt-3">
                <ul class="base-pagination pagination"></ul>
              </nav>
            </div>
          </div>
        </div>
      </section>

      <!-- Edit Modal -->
      <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <form id="editProductForm" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="edit-id"/>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" id="edit-name" class="form-control" disabled/>
              </div>
              <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" id="edit-price" name="price"
                       class="form-control" required/>
              </div>
              <div class="mb-3">
                <label class="form-label">MOQ</label>
                <input type="number" id="edit-moq" name="moq" class="form-control"/>
              </div>
              <div class="mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" id="edit-stock" name="stock" class="form-control"/>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select id="edit-status" name="status" class="form-select">
                  <option value="instock">In Stock</option>
                  <option value="outofstock">Out of Stock</option>
                  <option value="discontinued">Discontinued</option>
                </select>
              </div>
              <div class="mb-3">

                <label class="form-label">Restock ETA</label>
                <input type="text" id="edit-restock" name="restock_eta" class="form-control"/>

                <label class="form-label">Packaging Info URL</label>
                <input type="url" id="edit-packaging-url" name="packaging_info_url" class="form-control"/>
              </div>
              <div class="mb-3">
                <label class="form-label">Safety/Data Sheet URL</label>
                <input type="url" id="edit-safety-url" name="safety_sheet_url" class="form-control"/>

              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="main-btn primary-btn btn-hover">Save</button>
            </div>
          </form>
        </div>
      </div>

      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
    </main>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/cJs/product-management.js"></script>
    <script src="assets/js/cJs/pagination.js"></script>
  </body>
</html>
