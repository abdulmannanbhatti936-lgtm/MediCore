document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert').forEach((alertEl) => {
    setTimeout(() => {
      if (window.bootstrap) bootstrap.Alert.getOrCreateInstance(alertEl).close();
    }, 4500);
  });
  const printBtn = document.querySelector('[data-print-report]');
  if (printBtn) printBtn.addEventListener('click', () => window.print());
});
