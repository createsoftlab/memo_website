document.addEventListener('DOMContentLoaded', function () {
  // Video Modal Autoplay & Stop Fix
  const videoModal = document.getElementById('videoSupportModal');
  const iframe = document.getElementById('supportVideoIframe');

  if (videoModal && iframe) {
    const originalSrc = iframe.src;

    videoModal.addEventListener('show.bs.modal', function () {
      iframe.src = originalSrc.includes('autoplay=1')
        ? originalSrc
        : originalSrc + '?autoplay=1';
    });

    videoModal.addEventListener('hide.bs.modal', function () {
      iframe.src = originalSrc;
    });
  }
});