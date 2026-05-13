document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("mortgage-form");
  const result = document.getElementById("emi_result");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const loan = parseFloat(document.getElementById("loan_amount").value);
      const rate = parseFloat(document.getElementById("interest_rate").value) / 100 / 12;
      const years = parseFloat(document.getElementById("loan_term").value) * 12;

      const emi = (loan * rate * Math.pow(1 + rate, years)) / (Math.pow(1 + rate, years) - 1);
      result.textContent = `Monthly EMI: ₹${emi.toFixed(2)}`;
    });
  }
});
