<?php
// portal/shipments.php

// 1) Load your base URL constant
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
  <title>Tharavix | Logistics & Shipping</title>

  <!-- ========== CSS Files ========== -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/lineicons.css" />
  <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
  <link rel="stylesheet" href="assets/css/fullcalendar.css" />
  <link rel="stylesheet" href="assets/css/main.css" />

  <style>
    /* minor spacing tweaks */
    .provider-select, .eta-input { width: 100%; }
    .save-btn { font-size: .9rem; }
  </style>

  <!-- Expose BASE_URL to JS -->
  <script>const BASE_URL = "<?= $BASE_URL ?>";</script>
</head>
<body>
  <!-- Preloader -->
  <div id="skeleton-loader"><div class="skeleton-block"></div></div>

  <!-- Sidebar -->
  <aside class="sidebar-nav-wrapper">
    <script src="assets/js/cJs/sidebar.js"></script>
  </aside>
  <div class="overlay"></div>

  <!-- Main content -->
  <main class="main-wrapper">
    <!-- Header -->
    <header class="header">
      <script src="assets/js/cJs/header.js"></script>
      <script src="assets/js/cJs/menuToggle.js"></script>
    </header>

    <!-- Shipments Section -->
    <section class="table-components">
      <div class="container-fluid">
        <!-- Title + Actions -->
        <div class="title-wrapper pt-30 mb-3">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h2 class="page-title">Logistics & Shipping</h2>
            </div>
            <div class="filter-bar col-md-6 text-end d-flex align-items-center justify-content-end">
              <label for="orderSearch" class="me-2 mb-0">Search:</label>
              <input id="orderSearch" type="text" class="form-control form-control-sm me-2"
                     style="width:120px;" placeholder="Order ID" />
              <button id="refreshShipments" class="btn btn-outline-primary btn-sm me-2">
                <i class="lni lni-reload"></i> Refresh
              </button>
              <button id="uploadManifestBtn" class="btn btn-outline-secondary btn-sm">
                <i class="lni lni-upload"></i> Upload Manifest
              </button>
            </div>
          </div>
        </div>

        <!-- Alerts -->
        <div id="shipmentAlerts" class="alert alert-warning d-none"></div>

        <!-- Shipments Table Card -->
        <div class="card-style mb-30">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0" id="shipmentsTable">
                <thead class="table-light">
                  <tr>
                    <th>Order #</th>
                    <th>Provider</th>
                    <th>Tracking #</th>
                    <th>ETA</th>
                    <th>Status</th>
                    <th>Last Update</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- injected by JS -->
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <nav class="p-3">
              <ul class="base-pagination pagination"></ul>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <!-- Manifest Upload Modal -->
    <div class="modal fade" id="manifestModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form id="manifestForm" class="modal-content" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Upload Shipment Manifest</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p class="small">
              CSV columns: <code>order_id,provider,tracking_no,eta(YYYY-MM-DD)</code>
            </p>
            <input type="file" name="manifest" accept=".csv" required class="form-control" />
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <script src="assets/js/cJs/footer.js"></script>
    </footer>
  </main>

  <!-- ========== JS Files ========== -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  
  <script src="assets/js/cJs/shipments.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
