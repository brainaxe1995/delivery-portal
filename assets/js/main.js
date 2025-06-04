(function () {
  /* ========= Preloader ======== */
  const preloader = document.querySelectorAll('#preloader')

  // Helper functions exposed globally
  window.showLoader = function () {
    const el = document.getElementById('preloader')
    if (el) el.style.display = 'flex'
  }

  window.hideLoader = function () {
    const el = document.getElementById('preloader')
    if (el) el.style.display = 'none'
  }

  window.addEventListener('load', function () {
    if (preloader.length) {
      window.hideLoader()
    }
  })

  /* ========= Add Box Shadow in Header on Scroll ======== */
  window.addEventListener('scroll', function () {
    const header = document.querySelector('.header')
    if (window.scrollY > 0) {
      header.style.boxShadow = '0px 0px 30px 0px rgba(200, 208, 216, 0.30)'
    } else {
      header.style.boxShadow = 'none'
    }
  })

  /* ========= sidebar toggle ======== */
  /*const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
  const mainWrapper = document.querySelector(".main-wrapper");
  const menuToggleButton = document.querySelector("#menu-toggle");
  const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
  const overlay = document.querySelector(".overlay");

  menuToggleButton.addEventListener("click", () => {
    sidebarNavWrapper.classList.toggle("active");
    overlay.classList.add("active");
    mainWrapper.classList.toggle("active");

    if (document.body.clientWidth > 1200) {
      if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {
        menuToggleButtonIcon.classList.remove("lni-chevron-left");
        menuToggleButtonIcon.classList.add("lni-menu");
      } else {
        menuToggleButtonIcon.classList.remove("lni-menu");
        menuToggleButtonIcon.classList.add("lni-chevron-left");
      }
    } else {
      if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {
        menuToggleButtonIcon.classList.remove("lni-chevron-left");
        menuToggleButtonIcon.classList.add("lni-menu");
      }
    }
  });
  overlay.addEventListener("click", () => {
    sidebarNavWrapper.classList.remove("active");
    overlay.classList.remove("active");
    mainWrapper.classList.remove("active");
  });*/
})();

document.addEventListener('DOMContentLoaded', function() {
  // Update the status text and its class
  function updateStatus(statusSpan, newStatus) {
    // List of potential status classes
    const statusClasses = [
      'status-new-order',
      'status-pending',
      'status-processing',
      'status-in-transit',
      'status-delivered',
      'status-returned',
      'status-refunded',
      'status-cancelled'
    ];

    // Remove any previously applied status classes
    statusClasses.forEach(cls => statusSpan.classList.remove(cls));

    // Generate the new status class based on the new status text
    const newClass = 'status-' + newStatus.toLowerCase().replace(/\s+/g, '-');
    statusSpan.classList.add(newClass);

    // Update the text
    statusSpan.textContent = newStatus;
  }

  // Attach click event to each dropdown item
  const dropdownItems = document.querySelectorAll('.dropdown-menu a.dropdown-item');
  dropdownItems.forEach(function(item) {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const newStatus = this.dataset.status || this.textContent.trim();
      const btnGroup = this.closest('.btn-group');
      if (btnGroup) {
        const statusSpan = btnGroup.querySelector('.status-text');
        if (statusSpan) {
          updateStatus(statusSpan, newStatus);
        }
      }
      // OPTIONAL: Add any code here to persist the new status to your backend.
    });
  });
});
