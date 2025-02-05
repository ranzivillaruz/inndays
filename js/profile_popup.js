// Updated JavaScript
let popupTimer;

function showPopup(type, message) {
  const popup = document.getElementById('popupMessage');
  const messageElement = popup.querySelector('.popup-message');

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

// Proper usage examples:
// When update SUCCEEDS:
// When your update is successful:
showPopup('success', 'Updated successfully!');

// When there's an error:
showPopup('error', 'Update failed!');