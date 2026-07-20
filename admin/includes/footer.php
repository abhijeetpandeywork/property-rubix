</main><!-- /adm-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<?= isset($extraScripts) ? $extraScripts : '' ?>
<script>
// Mobile sidebar toggle
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.getElementById('adminSidebar')?.classList.toggle('mobile-open');
});
// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', function(e) {
    if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
  });
});
</script>
</body>
</html>
