document.addEventListener("DOMContentLoaded", function () {
  const sliders = document.querySelectorAll(".property-slider.swiper-container");
  sliders.forEach(function (slider) {
    new Swiper(slider, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      pagination: {
        el: slider.querySelector(".swiper-pagination"),
        clickable: true,
      },
      navigation: {
        nextEl: slider.querySelector(".swiper-button-next"),
        prevEl: slider.querySelector(".swiper-button-prev"),
      },
      breakpoints: {
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      }
    });
  });
});
