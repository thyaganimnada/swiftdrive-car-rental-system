</div> <!-- End Main Content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("sidebarOverlay");
        const toggleBtn = document.getElementById("sidebarToggleBtn");
        const closeBtn = document.getElementById("closeSidebarBtn");
        const currentUrl = window.location.pathname.split('/').pop();

        // Auto Active Link based on URL
        document.querySelectorAll('.nav-item-custom').forEach(link => {
            if (link.getAttribute('href') === currentUrl) link.classList.add('active');
        });

        const toggleSidebar = () => {
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        };
        if (toggleBtn) toggleBtn.addEventListener("click", toggleSidebar);
        if (overlay) overlay.addEventListener("click", toggleSidebar);
        if (closeBtn) closeBtn.addEventListener("click", toggleSidebar);
    });
</script>
</body>

</html>