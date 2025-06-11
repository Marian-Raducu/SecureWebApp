
document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("darkToggle");
  toggle.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");
  });
});
