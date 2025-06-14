<?php
// portal/logistics-orders.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
$shipments=[];
$endpoint=rtrim(PROJECT_BASE_URL,"/")."/assets/cPhp/get_shipments_summary.php?per_page=20";
$resp=@file_get_contents($endpoint);
if($resp!==false){$shipments=json_decode($resp,true)||[];}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Tharavix | Logistics Orders</title>

    <!-- ========== All CSS files linkup (Bootstrap, theme, etc.) ========= -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/css/fullcalendar.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    
    <!-- (Optional) Additional styling for columns that emulate WooCommerce look -->
    <style>
      table.table thead th {
        vertical-align: middle;
        text-align: left;
      }
      table.table tbody td p {
        margin: 0;
      }
      .status-cancelled { background-color: #f8d7da; color: #721c24; }
      .status-pending   { background-color: #fff3cd; color: #856404; }
      .status-processing { background-color: #d1ecf1; color: #0c5460; }
      /* ... Add more .status-* classes as needed ... */
    </style>
    <!-- Provide BASE_URL constant for JS -->
    <script>
      // Must appear before any other JS
      window.BASE_URL = "<?php echo rtrim(PROJECT_BASE_URL, '/'); ?>";
    </script>
  </head>
  <body>
    <!-- ======== Preloader (optional) =========== -->
    <div id="skeleton-loader"><div class="skeleton-block"></div></div>
    <!-- ======== sidebar-nav start (unchanged) =========== -->
    <aside class="sidebar-nav-wrapper">
      <script src="assets/js/cJs/sidebar.js"></script>
    </aside>
    <div class="overlay"></div>
    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
      <!-- ========== header start ========== -->
      <header class="header">
        <script src="assets/js/cJs/header.js"></script>
        <script src="assets/js/cJs/menuToggle.js"></script>
      </header>
      <!-- ========== header end ========== -->

      <!-- ========== table components start ========== -->
      <section class="table-components">
        <div class="container-fluid">
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="title">
                  <h2>Logistics Orders</h2>
                </div>
              </div>
              <!-- end col -->
              <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
                <label for="orderSearch" class="me-2 mb-0">Search:</label>
                <input id="orderSearch" type="text" class="form-control form-control-sm"
                       style="width:120px;" placeholder="Order ID" />
                <button id="refreshShipments" class="btn btn-outline-primary btn-sm ms-2">Refresh</button>
              </div>
              <!-- end col -->
            </div>
          </div>

          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Data Table</h6>
                  <div class="table-responsive">
                    <table id="shipmentsTable" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th><h6>Order #</h6></th>
                          <th><h6>Total</h6></th>
                          <th><h6>Export/Status</h6></th>
                          <th><h6>Actions</h6></th>
                          <th><h6>Tracking Number</h6></th>
                          <th><h6>Origin</h6></th>
                        </tr>
                      </thead>
                      <tbody>
<?php foreach($shipments as $s): ?>
<tr>
  <td>#<?= htmlspecialchars($s["order_id"] ?? "") ?></td>
  <td><?= htmlspecialchars($s["total"] ?? "") ?></td>
  <td><?= htmlspecialchars($s["status"] ?? "") ?></td>
  <td><button class="btn btn-sm btn-primary view-btn" data-id="<?= htmlspecialchars($s["order_id"] ?? "") ?>"><i class="lni lni-eye"></i></button></td>
  <td><?= htmlspecialchars($s["tracking_no"] ?? "") ?></td>
  <td><?= htmlspecialchars($s["origin"] ?? "") ?></td>
</tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- end card -->
              </div>
            </div>
            <!-- Pagination -->
            <nav>
              <ul class="base-pagination pagination"></ul>
            </nav>
          </div>
        </div>
      </section>
      <!-- ========== table components end ========== -->

    <div class="modal fade" id="shipmentModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Shipment Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="shipmentDetailBody">Loading...</div>
        </div>
      </div>
    </div>
      <!-- ========== footer start =========== -->
      <footer class="footer">
        <script src="assets/js/cJs/footer.js"></script>
      </footer>
      <!-- ========== footer end =========== -->
    </main>

    <!-- ========== All Javascript files linkup ========= -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/Chart.min.js"></script>
    <script src="assets/js/dynamic-pie-chart.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/fullcalendar.js"></script>
    <script src="assets/js/jvectormap.min.js"></script>
    <script src="assets/js/world-merc.js"></script>
    <script src="assets/js/polyfill.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- JS to load logistics orders and pagination -->
    <script src="assets/js/cJs/logistics-orders.js"></script>
    <script src="assets/js/cJs/pagination.js"></script>
  </body>
</html>
