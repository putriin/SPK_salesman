document.addEventListener("DOMContentLoaded", () => {
  const passwordInput = document.getElementById("password");
  const toggleBtn = document.querySelector(".toggle-password");

  if (toggleBtn && passwordInput) {
    toggleBtn.addEventListener("click", () => {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleBtn.textContent = "👀";
      } else {
        passwordInput.type = "password";
        toggleBtn.textContent = "👁️";
      }
    });
  }
});
