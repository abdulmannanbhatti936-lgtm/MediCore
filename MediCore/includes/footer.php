<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (empty($isAuthPage) && empty($isPublicPage)): ?>
        </div>
    </main>
</div>
<?php elseif (empty($isAuthPage)): ?>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>
