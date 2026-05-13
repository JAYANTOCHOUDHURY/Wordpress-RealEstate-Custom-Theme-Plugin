document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const mobileMenu = document.getElementById("mobile-menu");
  const closeMenu = document.getElementById("close-menu");
  const overlay = document.getElementById("menu-overlay");
  const toggleBtn = document.getElementById("toggle-dark");
  const isDark = localStorage.getItem('dark-mode') === 'true';
  const filterForm = document.getElementById('filter-form');

  if (filterForm) {
    filterForm.addEventListener('submit', function (e) {
      const formData = new FormData(filterForm);
      let hasFilter = false;

      for (let value of formData.values()) {
        if (value.trim() !== '') {
          hasFilter = true;
          break;
        }
      }

      // If all fields are empty, prevent form submit and redirect to homepage
      if (!hasFilter) {
        e.preventDefault();
        window.location.href = filterForm.action; // dynamically uses the action attribute
      }
    });
  }

  if (isDark) {
    document.body.classList.add('dark-mode');
    toggleBtn.checked = true;
  }

  toggleBtn.addEventListener('change', function () {
    if (this.checked) {
      document.body.classList.add('dark-mode');
      localStorage.setItem('dark-mode', 'true');
    } else {
      document.body.classList.remove('dark-mode');
      localStorage.setItem('dark-mode', 'false');
    }
  });

  menuToggle.addEventListener("click", function () {
    mobileMenu.classList.add("open");
    overlay.classList.add("active");
  });

  closeMenu.addEventListener("click", function () {
    mobileMenu.classList.remove("open");
    overlay.classList.remove("active");
  });

  overlay.addEventListener("click", function () {
    mobileMenu.classList.remove("open");
    overlay.classList.remove("active");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  if (document.querySelector('.featured-swiper')) {
    new Swiper('.featured-swiper', {
      loop: true, // Enable infinite loop
      autoplay: {
        delay: 2500, // Slide every 2.5 seconds
        disableOnInteraction: false, // Don't stop autoplay on interaction
      },
      slidesPerView: 1,
      spaceBetween: 20,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        768: {
          slidesPerView: 3
        },
        1024: {
          slidesPerView: 4
        }
      }
    });
  }
});
