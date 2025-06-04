// assets/js/cJs/header.js

// 1) Your header HTML template
const headerHTML = `
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-6">
        <div class="header-left d-flex align-items-center">
          <div class="menu-toggle-btn mr-15">
            <button id="menu-toggle" class="main-btn primary-btn btn-hover">
              <i class="lni lni-chevron-left me-2"></i> Menu
            </button>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-md-7 col-6">
        <div class="header-right">
          <!-- notification start -->
          <div class="notification-box ml-15 d-none d-md-flex">
            <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z" fill=""></path>
                <path d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z" fill=""></path>
              </svg>
              <span>0</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification">
              <!-- notifications injected here -->
            </ul>
          </div>
          <!-- notification end -->

          <!-- profile start -->
          <div class="profile-box ml-15">
            <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <div class="profile-info">
                <div class="info">
                  <div class="image">
                    <img src="assets/images/profile/profile-image.png" alt="Profile Image" />
                  </div>
                  <div>
                    <h6 class="fw-500">Adam Joe</h6>
                    <p>Supplier</p>
                  </div>
                </div>
              </div>
            </button>
          </div>
          <!-- profile end -->
        </div>
      </div>
    </div>
  </div>
`;

/**
 * Injects the headerHTML into <header class="header"> on every page.
 */
function injectHeader() {
  const headerEl = document.querySelector('header.header');
  if (headerEl) headerEl.innerHTML = headerHTML;
}

/**
 * Fetches notifications from the dashboard summary API
 * and populates the bell badge and dropdown.
 */
function loadNotifications() {
  fetch(`${BASE_URL}/assets/cPhp/get_dashboard_summary.php`)
    .then(res => res.json())
    .then(data => {
      // 1) Update badge count
      const badge = document.querySelector('#notification span');
      if (badge) badge.textContent = data.notifications.length;

      // 2) Populate dropdown list
      const dropdown = document.querySelector('#notification + .dropdown-menu');
      if (!dropdown) return;
      dropdown.innerHTML = '';
      data.notifications.forEach(n => {
        const iconClass = n.type === 'delay'   ? 'lni-alarm'
                        : n.type === 'refund'  ? 'lni-reload'
                        :                        'lni-cart-full';
        const li = document.createElement('li');
        li.innerHTML = `
          <a href="${n.link}">
            <i class="lni ${iconClass} me-2"></i>${n.message}
          </a>`;
        dropdown.appendChild(li);
      });
    })
    .catch(err => console.error('Notif load error:', err));
}

// Run on every page load
document.addEventListener('DOMContentLoaded', () => {
  injectHeader();
  loadNotifications();
});
