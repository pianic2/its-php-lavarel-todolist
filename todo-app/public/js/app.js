document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (event.defaultPrevented || !form.checkValidity()) {
        return;
      }

      const submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');

      if (!submitter) {
        return;
      }

      submitter.classList.add('is-loading');
      submitter.setAttribute('aria-busy', 'true');
      submitter.disabled = true;
    });
  });
});
