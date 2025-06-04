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
  <div id="preloader"><div class="spinner"></div></div>
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
        <div class="title-wrapper pt-30 mb-3">
          <div class="row align-items-center">
            <div class="col-md-6"><h2>Invoices</h2></div>
          </div>
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
