<?php
// portal/inventory-management.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon"/>
    <title>Tharavix | Inventory Management</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/lineicons.css"/>
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.css"/>
    <link rel="stylesheet" href="assets/css/main.css"/>

    <style>
      td { white-space: nowrap; vertical-align: top; }
      .badge { font-size: .9em; }
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

          <!-- Title & Refresh -->
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6"><h2 class="page-title">Inventory Management</h2></div>
              <div class="col-md-6 text-end">
                <button id="refreshInventory" class="main-btn primary-btn btn-hover btn-sm">
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
                  <label for="invSearch" class="me-2 mb-0">Search:</label>
                  <input id="invSearch" type="text"
                         class="form-control form-control-sm"
                         placeholder="Name or ID..." style="width:200px;"/>
                </div>
                <div class="d-flex align-items-center">
                  <label for="invStatus" class="me-2 mb-0">Status:</label>
                  <select id="invStatus" class="form-select form-select-sm" style="width:150px;">
                    <option value="">All</option>
                    <option value="instock">In Stock</option>
                    <option value="outofstock">Out of Stock</option>
                  </select>
                </div>
              </div>

              <!-- Inventory Table -->
              <div class="table-responsive">
                <table id="inventory-table" class="table table-striped table-hover align-middle mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Stock</th>
                      <th>Safety Stock</th>
                      <th>Reorder Threshold</th>
                      <th>Status</th>
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

      <!-- Edit Thresholds Modal -->
      <div class="modal fade" id="thresholdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Inventory Settings</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="invProductId" />
              <div class="mb-3">
                <label class="form-label">Safety Stock</label>
                <input type="number" class="form-control" id="safetyStock" />
              </div>
              <div class="mb-3">
                <label class="form-label">Reorder Threshold</label>
                <input type="number" class="form-control" id="reorderThreshold" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="saveThresholds">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- History Modal -->
      <div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Stock History</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <table class="table table-striped table-hover" id="historyTable">
                <thead><tr><th>Qty</th><th>Reason</th><th>Time</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
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

    <!-- Portal Base URL for AJAX -->
    <script>
      window.BASE_URL = "<?php
        include 'assets/cPhp/server-config.php';
        echo rtrim(PROJECT_BASE_URL, '/');
      ?>";
    </script>

    <!-- Page-specific JS -->
    <script src="assets/js/cJs/inventory-management.js"></script>
    <script src="assets/js/cJs/pagination.js"></script>
  </body>
</html>
