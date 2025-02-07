let popupTimer;

function showPopup(type, message) {
    const popup = document.getElementById('popupMessage');
    const messageElement = popup.querySelector('.popup-message');

    if (!popup || !messageElement) return;

    // Clear existing classes and timer
    popup.className = 'popup hidden';
    clearTimeout(popupTimer);

    // Set content and styling
    popup.classList.add(type);
    messageElement.textContent = message;
    popup.classList.remove('hidden');

    // Auto-hide after 3 seconds
    popupTimer = setTimeout(() => {
        popup.classList.add('hidden');
    }, 3000);
}

// Hide the popup when the user clicks anywhere on the page or on an input field
document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("popupMessage");

    // If the popup exists, set up the click event
    if (popup) {
        // Hide popup when user clicks anywhere on the page (outside the popup)
        document.body.addEventListener("click", function(event) {
            if (!popup.contains(event.target)) {
                // Fade out the popup when clicked outside
                popup.classList.add('hidden');
            }
        });
    }
});
