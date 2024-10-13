// Get all the slides, previous button, and next button
const slides = document.querySelectorAll('.slide');
const prevButton = document.querySelector('.arrow-back');
const nextButton = document.querySelector('.arrow-forward');
let currentSlideIndex = 0;

// Function to show a specific slide
function showSlide(index) {
    const slideshowImageBox = document.querySelector('.slideshow-image-box');
    
    // Update the transform property to slide to the correct index
    slideshowImageBox.style.transform = `translateX(-${index * 100}%)`;
}

// Function to go to the next slide
function nextSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % slides.length; // Loop back to the first slide
    showSlide(currentSlideIndex);
}

// Function to go to the previous slide
function prevSlide() {
    currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length; // Loop back to the last slide
    showSlide(currentSlideIndex);
}

// Event listeners for previous and next buttons
nextButton.addEventListener('click', nextSlide);
prevButton.addEventListener('click', prevSlide);

// Auto-slide every 5 seconds
setInterval(nextSlide, 5000);

// Show the first slide on page load
showSlide(currentSlideIndex);
