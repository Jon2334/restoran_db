        </div> <!-- End Main Content -->
    </div> <!-- End Page Content -->
</div> <!-- End Wrapper -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (For Chart.js or DataTables if needed later, though we try to use Vanilla JS) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Toggle sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    });
</script>
</body>
</html>