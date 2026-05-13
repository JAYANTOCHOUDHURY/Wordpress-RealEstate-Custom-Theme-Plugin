    </div> <!-- close .site-content -->

    <footer class="site-footer">
      <div class="footer-content container">
        <div class="footer-left">
          <p><strong>JoyWSI Real Estate</strong><br>
          Helping you find your perfect home. Browse properties, connect with agents, and make your move today.</p>
        </div>

        <div class="footer-center">
          <ul class="footer-social">
            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
          </ul>
        </div>

        <div class="footer-right">
          <p>&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        </div>
      </div>

      <?php wp_footer(); ?>

      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const menuToggle = document.getElementById("menu-toggle");
          const mobileMenu = document.getElementById("mobile-menu");
          const closeMenu = document.getElementById("close-menu");
          const overlay = document.getElementById("menu-overlay");

          const filterForm = document.querySelector(".property-filter");
          const pagination = document.querySelector(".pagination");

          const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                entry.target.classList.add("visible");
              }
            });
          }, {
            threshold: 0.2
          });

          if (filterForm) {
            observer.observe(filterForm);
          }

          if (pagination) {
            const paginationObserver = new IntersectionObserver(entries => {
              entries.forEach(entry => {
                if (entry.isIntersecting) {
                  pagination.classList.add("visible");
                  paginationObserver.unobserve(pagination);
                }
              });
            }, {
              threshold: 0.2
            });
            paginationObserver.observe(pagination);
          }

          if (menuToggle && mobileMenu && closeMenu && overlay) {
            menuToggle.addEventListener("click", () => {
              mobileMenu.classList.add("active");
              overlay.classList.add("active");
              document.body.style.overflow = "hidden";
            });

            const closeSidebar = () => {
              mobileMenu.classList.remove("active");
              overlay.classList.remove("active");
              document.body.style.overflow = "";
            };

            closeMenu.addEventListener("click", closeSidebar);
            overlay.addEventListener("click", closeSidebar);
          }
        });
      </script>
    </footer>

  </div> <!-- close .site-wrapper -->
</body>
</html>
