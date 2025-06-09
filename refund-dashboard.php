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
  <title>Refund Dashboard</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/lineicons.css" />
  <link rel="stylesheet" href="assets/css/main.css" />
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
        <div class="row pt-30 mb-3 align-items-center">
          <div class="col"><h2>Refund Dashboard</h2></div>
          <div class="col-auto">
            <select id="statusFilter" class="form-select form-select-sm">
              <option value="">All Statuses</option>
              <option value="requested">Requested</option>
              <option value="approved">Approved</option>
              <option value="denied">Denied</option>
              <option value="refunded">Refunded</option>
            </select>
          </div>
        </div>
        <div class="card-style mb-30">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Reason</th>
                  <th>Proof</th>
                  <th>Status History</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="refundTable"></tbody>
            </table>
          </div>
        </div>
        <nav class="p-3">
          <ul class="base-pagination pagination"></ul>
        </nav>
      </div>
    </section>
    <footer class="footer">
      <script src="assets/js/cJs/footer.js"></script>
    </footer>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script> const BASE_URL = "<?= $BASE_URL ?>"; </script>
  <script src="assets/js/cJs/refund_requests.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
