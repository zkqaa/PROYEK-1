document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.add('page-loaded');

  const links = document.querySelectorAll('a[href]');

  links.forEach(link => {
    link.addEventListener('click', function (e) {
      const href = this.getAttribute('href');

      if (
        !href ||
        href.startsWith('#') ||
        href.startsWith('javascript:') ||
        this.hasAttribute('target') ||
        this.hasAttribute('download')
      ) {
        return;
      }

      const url = new URL(this.href, window.location.href);

      if (url.origin !== window.location.origin) {
        return;
      }

      e.preventDefault();

      document.body.classList.remove('page-loaded');
      document.body.classList.add('page-leaving');

      setTimeout(() => {
        window.location.href = this.href;
      }, 250);
    });
  });
});