document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("filter-form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    // Convert form data to URL query string
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
      params.append(key, value);
    }

    fetch(ajax_object.ajax_url + "?" + params.toString(), {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    })
      .then(response => response.text())
      .then(html => {
        const container = document.getElementById("property-results");
        container.innerHTML = html;
        container.classList.add("visible");
      });
  });
});
