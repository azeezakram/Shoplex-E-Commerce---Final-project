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
