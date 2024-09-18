// Toggle the display of the upload box
function toggleUploadBox() {
    const uploadBox = document.getElementById('uploadBox');
    
    // Check current visibility and toggle it
    if (uploadBox.style.display === 'block') {
        uploadBox.style.display = 'none';
    } else {
        uploadBox.style.display = 'block';
    }
}

// Optional: Close the upload box when clicking outside
window.addEventListener('click', function(event) {
    const uploadBox = document.getElementById('uploadBox');
    const imageSearchButton = document.querySelector('.image-search-button');
    
    // If the click is outside the button or the box, close the box
    if (!imageSearchButton.contains(event.target)) {
        uploadBox.style.display = 'none';
    }
});

// Toggle the display of the upload box
function toggleProfilePopupBox() {
    const profilePopup = document.getElementById('profilePopup');
    
    // Check current visibility and toggle it
    if (profilePopup.style.display === 'flex') {
        profilePopup.style.display = 'none';
    } else {
        profilePopup.style.display = 'flex';
    }
}

// Optional: Close the upload box when clicking outside
window.addEventListener('click', function(event) {
    const profilePopup = document.getElementById('profilePopup');
    const profilePicture = document.getElementsByClassName('current-user-picture');
    
    // If the click is outside the button or the box, close the box
    if (!profilePicture.contains(event.target)) {
        profilePopup.style.display = 'none';
    }
});