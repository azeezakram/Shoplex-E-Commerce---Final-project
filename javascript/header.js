//Search by image popup animation and transition

const uploadBox = document.getElementById('uploadBox');
const imageSearchButton = document.querySelector('.image-search-button');

imageSearchButton.addEventListener('mouseover', function() {
    uploadBox.style.display = 'block';   
});

imageSearchButton.addEventListener('mouseout', function() {
    uploadBox.style.display = 'none';   
});



//Profile poup animation and transition

const profilePopup = document.getElementById('profilePopup');
const profileButton = document.getElementById('profileButton');

profileButton.addEventListener('mouseover', function() {
        profilePopup.style.display = 'block';   
});

profileButton.addEventListener('mouseout', function() {
        profilePopup.style.display = 'none'; 
});



//Side navbar animation and transition

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


let lastScrollTop = 0; 
const navbar = document.querySelector('.nav-bar');

window.addEventListener('scroll', function() {
    const currentScrollTop = window.scrollY; 

    if (currentScrollTop > lastScrollTop && currentScrollTop > 60) { 
        navbar.classList.add('hide'); 
        navbar.classList.add('scroll-down'); 
    } else if (currentScrollTop < lastScrollTop) {
        navbar.classList.remove('hide'); 
        navbar.classList.remove('scroll-down'); 
    }

    lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; 
});


document.addEventListener('DOMContentLoaded', function() {
    const categorySection = document.querySelector('.sidenav-category-section');
    const label = categorySection.querySelector('.dropdown-label');
    
    label.addEventListener('click', function() {
        
        categorySection.classList.toggle('open');
    });
});
