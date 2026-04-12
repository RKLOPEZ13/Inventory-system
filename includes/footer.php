<?php // includes/footer.php ?>
    </div><!-- /.main-content -->
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>

<script src="../assets/js/app.js"></script>
<?php if (isset($extra_js)): ?>
<script src="../assets/js/<?= htmlspecialchars($extra_js) ?>"></script>
<?php endif; ?>
</body>
</html>
