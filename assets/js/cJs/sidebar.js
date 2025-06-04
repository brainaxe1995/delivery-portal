// sidebar.js

// Define the full sidebar HTML structure
const sidebarHTML = `
  <div class="navbar-logo">
    <a href="/">
      <img src="assets/images/logo/logo.png" alt="logo" />
    </a>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="index.php">
          <span class="icon">
            <!-- Dashboard icon SVG -->
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.66666 4.16667C1.66666 3.24619 2.41285 2.5 3.33332 2.5H16.6667C17.5872 2.5 18.3333 3.24619 18.3333 4.16667V9.16667C18.3333 10.0872 17.5872 10.8333 16.6667 10.8333H3.33332C2.41285 10.8333 1.66666 10.0872 1.66666 9.16667V4.16667Z" />
              <path d="M1.875 13.75C1.875 13.4048 2.15483 13.125 2.5 13.125H17.5C17.8452 13.125 18.125 13.4048 18.125 13.75C18.125 14.0952 17.8452 14.375 17.5 14.375H2.5C2.15483 14.375 1.875 14.0952 1.875 13.75Z" />
              <path d="M2.5 16.875C2.15483 16.875 1.875 17.1548 1.875 17.5C1.875 17.8452 2.15483 18.125 2.5 18.125H17.5C17.8452 18.125 18.125 17.8452 18.125 17.5C18.125 17.1548 17.8452 16.875 17.5 16.875H2.5Z" />
            </svg>
          </span>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <!-- Orders -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_orders" aria-controls="ddmenu_orders" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon">
            <!-- Orders icon SVG -->
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
              <path d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
            </svg>
          </span>
          <span class="text">Orders</span>
        </a>
        <ul id="ddmenu_orders" class="collapse dropdown-nav">
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
          <span class="icon">
            <!-- Returns icon SVG -->
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
              <path d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
            </svg>
          </span>
          <span class="text">Returns & Refunds</span>
        </a>
        <ul id="ddmenu_returns" class="collapse dropdown-nav">
          <li><a href="returned-orders.php">Returned Orders</a></li>
          <li><a href="refund-dashboard.php">Refund Dashboard</a></li>
          <li><a href="refunded-orders.php">Refunded Orders</a></li>
        </ul>
      </li>

      <!-- Products & Inventory -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_products" aria-controls="ddmenu_products" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon">
            <!-- Products icon SVG -->
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
              <path d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
            </svg>
          </span>
          <span class="text">Products & Inventory</span>
        </a>
        <ul id="ddmenu_products" class="collapse dropdown-nav">
          <li><a href="product-management.php">Product Management</a></li>
          <li><a href="inventory-management.php">Inventory Management</a></li>
          <li><a href="product-requests.php">Product Requests</a></li>
        </ul>
      </li>

      <!-- Logistics -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_logistics" aria-controls="ddmenu_logistics" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon">
            <!-- Logistics icon SVG -->
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
              <path d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
            </svg>
          </span>
          <span class="text">Logistics</span>
        </a>
        <ul id="ddmenu_logistics" class="collapse dropdown-nav">
          <li><a href="shipments.php">Shipments</a></li>
          <li><a href="logistics-orders.html">Logistics Orders</a></li>
        </ul>
      </li>

      <!-- Sourcing & Pricing -->
      <li class="nav-item nav-item-has-children">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_sourcing" aria-controls="ddmenu_sourcing" aria-expanded="false" aria-label="Toggle navigation">
          <span class="icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 1.667L12.5 6.667H17.5L13.75 10L15 15L10 12.5L5 15L6.25 10L2.5 6.667H7.5L10 1.667Z" />
            </svg>
          </span>
          <span class="text">Sourcing & Pricing</span>
        </a>
        <ul id="ddmenu_sourcing" class="collapse dropdown-nav">
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
          <span class="icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4.16667C2 3.24619 2.74619 2.5 3.66667 2.5H16.3333C17.2538 2.5 18 3.24619 18 4.16667V15.8333C18 16.7538 17.2538 17.5 16.3333 17.5H3.66667C2.74619 17.5 2 16.7538 2 15.8333V4.16667Z" />
              <path d="M2 6.66667H18" />
            </svg>
          </span>
          <span class="text">Payments & Billing</span>
        </a>
        <ul id="ddmenu_pay" class="collapse dropdown-nav">
          <li><a href="invoices.php">Invoices</a></li>
          <li><a href="payment-terms.php">Payment Terms</a></li>
        </ul>
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
