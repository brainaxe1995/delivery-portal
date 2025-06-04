// footer.js

// Define the inner HTML content for the footer
const footerHTML = `
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 order-last order-md-first">
        <div class="copyright text-center text-md-start">
          <p class="text-sm">
            Designed and Developed by
            <a href="https://Tharavix.com" rel="nofollow" target="_blank">
              Tharavix
            </a>
          </p>
        </div>
      </div>
      <!-- end col-->
      <div class="col-md-6">
        <div class="terms d-flex justify-content-center justify-content-md-end">
          <a href="#0" class="text-sm">Term & Conditions</a>
          <a href="#0" class="text-sm ml-15">Privacy & Policy</a>
        </div>
      </div>
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
`;

// Wait for the DOM to be fully loaded before injecting the footer content
document.addEventListener('DOMContentLoaded', function() {
  const footerContainer = document.querySelector('footer.footer');
  if (footerContainer) {
    footerContainer.innerHTML = footerHTML;
  }
});
