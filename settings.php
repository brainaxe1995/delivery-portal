<?php
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
  <title>Portal | Settings</title>
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
        <h3 class="mb-3">Portal Settings</h3>
        <form id="settingsForm" class="card-style p-3" style="max-width:600px;">
          <div class="mb-3">
            <label class="form-label">Shipping API Key</label>
            <input id="shipping_key" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">WooCommerce Consumer Key</label>
            <input id="woocommerce_ck" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">WooCommerce Consumer Secret</label>
            <input id="woocommerce_cs" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Store URL</label>
            <input id="store_url" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Language</label>
            <input id="language" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Time Zone</label>
            <input id="time_zone" type="text" class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Currency</label>
            <input id="currency" type="text" class="form-control" />
          </div>
          <button class="btn btn-primary">Save</button>
        </form>
      </div>
    </section>
    <footer class="footer">
      <script src="assets/js/cJs/footer.js"></script>
    </footer>
  </main>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/cJs/settings.js"></script>
</body>
</html>
