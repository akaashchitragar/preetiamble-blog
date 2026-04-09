<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/head.php';
?>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="max-w-7xl mx-auto px-5 md:px-8">

    <?php require_once __DIR__ . '/includes/hero.php'; ?>

    <div class="mb-10 border-t border-[#DDD0BC]"></div>

    <?php require_once __DIR__ . '/includes/blog_grid.php'; ?>

    <?php require_once __DIR__ . '/includes/pagination.php'; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
