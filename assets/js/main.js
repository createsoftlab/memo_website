document.addEventListener('DOMContentLoaded', () => {
  // Tribute selection logic (index.html)
  const tributeBtns = document.querySelectorAll('.tribute-opt-btn');
  let selectedType = 'flower';

  tributeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      tributeBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      selectedType = this.getAttribute('data-type');
    });
  });

  const form = document.getElementById('tributeForm');
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const txt = document.getElementById('tributeText').value.trim();
      if (!txt) return alert('Please enter a tribute.');

      const icon = selectedType === 'candle' ? 'bi-fire' : selectedType === 'note' ? 'bi-feather' : 'bi-flower1';
      const container = document.getElementById('tributesStream');
      
      const div = document.createElement('div');
      div.className = 'card mb-3 border-0 bg-white shadow-sm';
      div.innerHTML = `
        <div class="card-body d-flex gap-3">
          <div class="fs-3 text-memorial"><i class="bi ${icon}"></i></div>
          <div>
            <h6 class="mb-1 fw-bold">A visitor left a tribute</h6>
            <p class="mb-1 text-secondary small">${txt}</p>
            <small class="text-muted">Just now</small>
          </div>
        </div>
      `;
      container.prepend(div);
      document.getElementById('tributeText').value = '';
    });
  }
});


// Sidebar Actions Functionality
document.addEventListener('DOMContentLoaded', () => {
  // 1. Share on Facebook Logic
  const shareFbBtn = document.getElementById('shareFbBtn');
  if (shareFbBtn) {
    shareFbBtn.addEventListener('click', () => {
      const currentUrl = encodeURIComponent(window.location.href);
      window.open(`https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`, '_blank', 'width=600,height=400');
    });
  }

  // 2. Notification Subscription Toggle Logic
  const subscribeBtn = document.getElementById('subscribeBtn');
  const subStatus = document.getElementById('subStatus');

  if (subscribeBtn && subStatus) {
    subscribeBtn.addEventListener('click', () => {
      const isSubscribed = subscribeBtn.classList.contains('subscribed');

      if (!isSubscribed) {
        subscribeBtn.textContent = 'Unsubscribe';
        subscribeBtn.classList.add('subscribed');
        subscribeBtn.classList.replace('btn-outline-memorial-dark', 'btn-secondary');
        subStatus.textContent = 'You are subscribed to notifications';
        subStatus.classList.replace('text-muted', 'text-success');
      } else {
        subscribeBtn.textContent = 'Subscribe';
        subscribeBtn.classList.remove('subscribed');
        subscribeBtn.classList.replace('btn-secondary', 'btn-outline-memorial-dark');
        subStatus.textContent = 'You are not subscribed';
        subStatus.classList.replace('text-success', 'text-muted');
      }
    });
  }
});