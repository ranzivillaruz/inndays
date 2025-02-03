const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});
document.addEventListener("DOMContentLoaded", function () {
  let popup = document.getElementById("popupMessage");

  if (popup) {
      console.log("Popup found, setting timeout..."); // Debug log

      // Auto-hide after 3 seconds
      setTimeout(() => {
          popup.style.opacity = "0";  // Fade out
          setTimeout(() => {
              popup.style.display = "none"; // Hide completely
          }, 500); 
      }, 3000);

      // Hide when clicking an input field
      document.querySelectorAll("input").forEach(input => {
          input.addEventListener("click", function () {
              console.log("Input clicked, hiding popup"); // Debug log
              popup.style.opacity = "0";
              setTimeout(() => {
                  popup.style.display = "none";
              }, 500);
          });
      });
  } else {
      console.log("Popup not found!"); // Debug log
  }
});
document.querySelector('input[name="contact"]').addEventListener('input', function (e) {
  e.target.value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
});
