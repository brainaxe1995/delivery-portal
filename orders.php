<?php
// portal/orders.php
require_once __DIR__ . '/assets/cPhp/config/bootstrap.php';
require_once __DIR__ . '/assets/cPhp/server-config.php';
$BASE_URL = rtrim(PROJECT_BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Management | Portal</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <!-- CSS -->
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
        <!-- Title & Filter -->
        <div class="row align-items-center pt-30 mb-3">
          <div class="col">
            <h2>Order Management</h2>
          </div>
          <div class="filter-bar col-auto d-flex align-items-center">
            <label for="statusFilter" class="me-2 mb-0">Status:</label>
            <select id="statusFilter" class="form-select form-select-sm">
              <option value="new">New Orders</option>
              <option value="pending">Pending Orders</option>
              <option value="processing">Processing Orders</option>
              <option value="on-hold">On-Hold Orders</option>
              <option value="in-transit">Orders in Transit</option>
              <option value="completed">Delivered Orders</option>
              <option value="returned">Returned Orders</option>
              <option value="refunded">Refunded Orders</option>
              <option value="cancelled">Cancelled Orders</option>
            </select>
            <label for="orderSearch" class="ms-3 me-2 mb-0">Search:</label>
            <input id="orderSearch" type="text"
                   class="form-control form-control-sm" style="width:120px;"
                   placeholder="Order ID" />
          </div>
        </div>

        <!-- Table Card -->
        <div class="card-style mb-30">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="orders-body">
                  <!-- JS will inject here -->
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
  </main>

  <!-- Track Modal -->
  <div class="modal fade" id="trackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="trackForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Tracking Number</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="trackOrderId" name="order_id" />
          <div class="mb-3">
            <label for="trackingCode" class="form-label">Tracking Number</label>
            <input type="text" id="trackingCode" name="tracking_code" class="form-control" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Tracking</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Comment Modal -->
  <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="commentForm" class="modal-content" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Comment & File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="commentOrderId" name="order_id" />
          <div class="mb-3">
            <label for="commentText" class="form-label">Comment</label>
            <textarea id="commentText" name="comment" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="commentFile" class="form-label">Attach File</label>
            <input id="commentFile" name="file" type="file" class="form-control" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <footer class="footer">
    <script src="assets/js/cJs/footer.js"></script>
  </footer>

  <!-- JS libs -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script> window.BASE_URL = "<?= $BASE_URL ?>"; </script>
  <script src="assets/js/cJs/orders.js"></script>
  <script src="assets/js/cJs/pagination.js"></script>
</body>
</html>
