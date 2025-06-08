// sidebar.js

// Define the full sidebar HTML structure
const sidebarHTML = `
  <div class="navbar-logo">
    <a href="/">
      <img src="assets/images/logo/logo.png" alt="logo" />
    </a>
  </div>
  <nav class="sidebar-nav">
    <ul id="sidebar-menu">
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="index.php">
          <span class="icon"><i class="lni lni-dashboard"></i></span>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="analytics.php">
          <span class="icon"><i class="lni lni-bar-chart"></i></span>
          <span class="text">Analytics</span>
        </a>
      </li>
      <!-- Orders -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_orders" aria-controls="ddmenu_orders" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon"><i class="lni lni-cart"></i></span>
          <span class="text">Orders</span>
        </a>
        <ul id="ddmenu_orders" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="new-orders.php">New Orders</a></li>
          <li><a href="pending-orders.php">Pending Orders</a></li>
          <li><a href="on-hold-orders.php">On-Hold Orders</a></li>
          <li><a href="in-transit-orders.php">In-Transit Orders</a></li>
          <li><a href="cancelled-orders.php">Cancelled Orders</a></li>
          <li><a href="delivered-orders.php">Delivered Orders</a></li>
          <li><a href="orders.php">Orders</a></li>
        </ul>
      </li>

      <!-- Returns & Refunds -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_returns" aria-controls="ddmenu_returns" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon"><i class="lni lni-reload"></i></span>
          <span class="text">Returns & Refunds</span>
        </a>
        <ul id="ddmenu_returns" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="returned-orders.php">Returned Orders</a></li>
          <li><a href="refund-dashboard.php">Refund Dashboard</a></li>
          <li><a href="refunded-orders.php">Refunded Orders</a></li>
        </ul>
      </li>

      <!-- Products & Inventory -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_products" aria-controls="ddmenu_products" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon"><i class="lni lni-package"></i></span>
          <span class="text">Products & Inventory</span>
        </a>
        <ul id="ddmenu_products" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="product-management.php">Product Management</a></li>
          <li><a href="inventory-management.php">Inventory Management</a></li>
          <li><a href="product-requests.php">Product Requests</a></li>
        </ul>
      </li>

      <!-- Logistics -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_logistics" aria-controls="ddmenu_logistics" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon"><i class="lni lni-delivery"></i></span>
          <span class="text">Logistics</span>
        </a>
        <ul id="ddmenu_logistics" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="shipments.php">Shipments</a></li>
          <li><a href="logistics-orders.html">Logistics Orders</a></li>
        </ul>
      </li>

      <!-- Sourcing & Pricing -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_sourcing" aria-controls="ddmenu_sourcing" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon"><i class="lni lni-invest-monitor"></i></span>
          <span class="text">Sourcing & Pricing</span>
        </a>
        <ul id="ddmenu_sourcing" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="new-product-requests.php">Product Requests</a></li>
          <li><a href="supplier-pricing.php">Supplier Pricing</a></li>
          <li><a href="lead-times.php">Lead Times</a></li>
          <li><a href="factory-documents.php">Factory Docs</a></li>
        </ul>
      </li>

      <span class="divider"><hr /></span>
      <!-- Payments & Billing -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_pay" aria-controls="ddmenu_pay" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon"><i class="lni lni-credit-cards"></i></span>
          <span class="text">Payments & Billing</span>
        </a>
        <ul id="ddmenu_pay" class="collapse dropdown-nav" data-bs-parent="#sidebar-menu">
          <li><a href="invoices.php">Invoices</a></li>
          <li><a href="payment-terms.php">Payment Terms</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="settings.php">
          <span class="icon"><i class="lni lni-cog"></i></span>
          <span class="text">Settings</span>
        </a>
      </li>
      <!-- Additional sidebar sections can be added here -->
    </ul>
  </nav>
`;

/**
 * Inject the sidebar HTML into the container
 */
function injectSidebar() {
  const sidebarContainer = document.querySelector('.sidebar-nav-wrapper');
  if (sidebarContainer) {
    sidebarContainer.innerHTML = sidebarHTML;
  }
}

/**
 * Set the active menu item based on the current URL.
 * For submenu links, this code adds the "active" class to the <a> element
 * so that the theme's existing CSS (.dropdown-nav ul li a.active) applies.
 */
function setActiveMenu() {
  // Get the current file name from the URL; remove query parameters.
  let currentPage = window.location.pathname.split('/').pop().split('?')[0];
  if (!currentPage) {
    currentPage = 'index.php';
  }

  // Select all sidebar links
  const sidebarLinks = document.querySelectorAll('.sidebar-nav a');

  sidebarLinks.forEach(link => {
    const linkHref = link.getAttribute('href');
    
    // Use endsWith to match currentPage with linkHref
    if (currentPage.endsWith(linkHref)) {
      // Add active class to the parent <li> to set row highlight
      const activeLi = link.closest('li');
      if (activeLi) {
        activeLi.classList.add('active');
      }
      
      // If the link is inside a dropdown submenu, add active class to the <a> element.
      if (link.closest('ul.dropdown-nav')) {
        link.classList.add('active');
      }
      
      // Also, if the link is within a dropdown, expand the submenu
      const parentUl = link.closest('ul.dropdown-nav');
      if (parentUl) {
        parentUl.classList.add('show');
        const parentLi = parentUl.closest('li.nav-item-has-children');
        if (parentLi) {
          parentLi.classList.add('active');
          const parentToggle = parentLi.querySelector('a.collapsed');
          if (parentToggle) {
            parentToggle.classList.remove('collapsed');
          }
        }
      }
    }
  });
}

// Execute the sidebar injection and active menu setting once the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
  injectSidebar();
  setActiveMenu();
});
