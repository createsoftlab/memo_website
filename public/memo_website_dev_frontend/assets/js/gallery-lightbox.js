document.addEventListener('DOMContentLoaded', () => {
  const modalImg = document.getElementById('modalImage');
  const galleryImgs = document.querySelectorAll('.gallery-img');

  galleryImgs.forEach(img => {
    img.addEventListener('click', function() {
      if (modalImg) {
        modalImg.src = this.src;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
      }
    });
  });
});