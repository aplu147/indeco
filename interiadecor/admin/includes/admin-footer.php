                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Admin Content -->
            
            <!-- Footer -->
            <footer class="admin-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p>&copy; <?= date('Y') ?> Interia Decor. All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p>Version 1.0.0</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- End Main Content -->
    </div>
    <!-- End Admin Wrapper -->
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Dropzone JS -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script src="<?= ADMIN_URL ?>/assets/js/admin.js"></script>
    
    <?php if (isset($customScripts)): ?>
        <?php foreach ($customScripts as $script): ?>
        <script src="<?= ADMIN_URL ?>/assets/js/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
    $(document).ready(function() {
        // Mark notification as read when clicked
        $('.notification-item.unread').click(function(e) {
            e.preventDefault();
            const notificationId = $(this).data('id');
            const url = $(this).attr('href');
            
            $.ajax({
                url: '<?= ADMIN_URL ?>/api/notifications.php?action=mark-read',
                method: 'POST',
                data: { id: notificationId },
                success: function() {
                    window.location.href = url;
                }
            });
        });
        
        // Mark all notifications as read
        $('.mark-all-read').click(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '<?= ADMIN_URL ?>/api/notifications.php?action=mark-all-read',
                method: 'POST',
                success: function() {
                    location.reload();
                }
            });
        });
        
        // Toggle sidebar
        $('.sidebar-toggle').click(function() {
            $('.admin-wrapper').toggleClass('sidebar-collapsed');
        });
    });
    </script>
</body>
</html>