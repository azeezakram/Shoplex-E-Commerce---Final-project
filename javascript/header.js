function toggleUploadBox() {
    const uploadBox = document.getElementById("uploadBox");
    uploadBox.style.display = (uploadBox.style.display === "none" || !uploadBox.style.display) ? "block" : "none";
}

window.addEventListener('click', function(event) {
    const uploadBox = document.getElementById('uploadBox');
    const imageSearchButton = document.querySelector('.image-search-button');
    
    if (!imageSearchButton.contains(event.target) && !uploadBox.contains(event.target)) {
        uploadBox.style.display = 'none';
    }
});

function toggleProfilePopupBox() {
    const profilePopup = document.getElementById("profilePopup");
    profilePopup.style.display = (profilePopup.style.display === "none" || !profilePopup.style.display) ? "block" : "none";
}

window.addEventListener('click', function(event) {
    const profilePopup = document.getElementById('profilePopup');
    const profilePicture = document.querySelector('.current-user-picture');
    
    if (!profilePicture.contains(event.target) && !profilePopup.contains(event.target)) {
        profilePopup.style.display = 'none';
    }
});

const sideNavBar = document.getElementById('sideNavBar');
const hamburgerButton = document.querySelector('.hamburger-button');
const closeBtn = document.getElementById('closeBtn');

hamburgerButton.addEventListener('click', function() {
    sideNavBar.classList.add('open');
    document.querySelector(".overlay").style.display = "block";
});

closeBtn.addEventListener('click', function() {
    sideNavBar.classList.remove('open');   
    document.querySelector(".overlay").style.display = "none";
});

window.addEventListener('click', function(event) {
    if (!sideNavBar.contains(event.target) && !hamburgerButton.contains(event.target)) {
        sideNavBar.classList.remove('open');
        document.querySelector(".overlay").style.display = "none";
    }
});
