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
    <title>Sales Analytics</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css" />
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
      <section class="section">
        <div class="container-fluid">
          <div class="title-wrapper pt-30 mb-3">
            <h2 class="page-title">Sales Analytics</h2>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="card-style mb-30">
                <h6 class="text-medium mb-25">Last 7 Days</h6>
                <canvas id="chartWeek" height="200"></canvas>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card-style mb-30">
                <h6 class="text-medium mb-25">Last 12 Months</h6>
                <canvas id="chartMonth" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
      </section>
      <footer class="footer"><script src="assets/js/cJs/footer.js"></script></footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/Chart.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>const BASE_URL = "<?= $BASE_URL ?>";</script>
    <script src="assets/js/cJs/analytics.js"></script>
  </body>
</html>
