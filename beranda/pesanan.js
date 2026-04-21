const tabButtons = document.querySelectorAll('.tab-btn');
const orderCards = document.querySelectorAll('.order-card');
const navMenu = document.getElementById('navMenu');
const navItems = navMenu.querySelectorAll('.nav-item');
const navHighlight = navMenu.querySelector('.nav-highlight');

function moveHighlight(target) {
    if (!target) return;
    navHighlight.style.width = target.offsetWidth + 'px';
    navHighlight.style.left = target.offsetLeft + 'px';
  }

  window.addEventListener('load', function () {
    const active = navMenu.querySelector('.nav-item.active');
    moveHighlight(active);
  });

  navItems.forEach(item => {
    item.addEventListener('mouseenter', function () {
      moveHighlight(this);
    });
  });

  navMenu.addEventListener('mouseleave', function () {
    const active = navMenu.querySelector('.nav-item.active');
    moveHighlight(active);
  });

tabButtons.forEach((button) => {
  button.addEventListener('click', () => {
    tabButtons.forEach((btn) => btn.classList.remove('active'));
    button.classList.add('active');

    const selectedTab = button.dataset.tab;

    orderCards.forEach((card) => {
      const status = card.dataset.status;

      if (selectedTab === 'semua') {
        card.style.display = 'block';
      } else {
        card.style.display = status === selectedTab ? 'block' : 'none';
      }
    });
  });
});