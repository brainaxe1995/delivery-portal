<?php
// portal/factory-documents.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Sourcing & Pricing | Factory Docs</title>
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
    <aside class="sidebar-nav-wrapper"><script src="assets/js/cJs/sidebar.js"></script></aside>
    <div class="overlay"></div>
    <main class="main-wrapper">
      <header class="header"><script src="assets/js/cJs/header.js"></script><script src="assets/js/cJs/menuToggle.js"></script></header>
      <section class="section">
        <div class="container-fluid">
          <h3 class="mb-3">Factory Certifications</h3>
          <form id="docForm" class="row g-2 mb-3" enctype="multipart/form-data">
            <div class="col-md-3"><input id="fd_supplier" name="supplier" class="form-control" placeholder="Supplier" required></div>
            <div class="col-md-3"><input id="fd_product" name="product" class="form-control" placeholder="Product"></div>
            <div class="col-md-4"><input type="file" name="document" class="form-control" required></div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Upload</button></div>
          </form>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
              <thead><tr><th>ID</th><th>Supplier</th><th>Product</th><th>File</th><th>Uploaded</th></tr></thead>
              <tbody id="docsBody"></tbody>
            </table>
          </div>
        </div>
      </section>
      <footer class="footer"><script src="assets/js/cJs/footer.js"></script></footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cJs/factory-docs.js"></script>
  </body>
</html>
