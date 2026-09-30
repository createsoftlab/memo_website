document.addEventListener('DOMContentLoaded', function () {
  const slides = document.querySelectorAll('.hero-slides .slide');
  let currentIndex = 0;
  const slideInterval = 4000; // සෑම තත්පර 4කට සැරයක් මාරු වේ

  function nextSlide() {
    if (slides.length > 0) {
      slides[currentIndex].classList.remove('active');
      currentIndex = (currentIndex + 1) % slides.length;
      slides[currentIndex].classList.add('active');
    }
  }

  if (slides.length > 0) {
    setInterval(nextSlide, slideInterval);
  }
});