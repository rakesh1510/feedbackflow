document.addEventListener('click', function (e) {
  if (e.target.matches('.copy-snippet')) {
    const targetId = e.target.getAttribute('data-target');
    const el = document.getElementById(targetId);
    if (!el) return;
    navigator.clipboard.writeText(el.innerText).then(() => {
      const old = e.target.innerText;
      e.target.innerText = 'Copied';
      setTimeout(() => e.target.innerText = old, 1200);
    });
  }
});
