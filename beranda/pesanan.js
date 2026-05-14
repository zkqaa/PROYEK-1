document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.tab-btn');

  function filterPesanan(statusAktif) {
    const cards = document.querySelectorAll('.order-card');

    cards.forEach(card => {
      const statusCard = card.getAttribute('data-status');

      // 🔴 ini yang penting
      if (statusCard === statusAktif) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const status = this.getAttribute('data-tab');
      filterPesanan(status);
    });
  });

  // default: hanya tampilkan status "semua"
  filterPesanan('semua');
});