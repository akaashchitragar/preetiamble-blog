<?php
$activeSlug = $_GET['category'] ?? 'all';

$catStmt = $pdo->query("
    SELECT c.name, c.slug, COUNT(p.id) AS post_count
    FROM categories c
    JOIN posts p ON p.category_id = c.id AND p.status = 'published'
    GROUP BY c.id, c.name, c.slug
    ORDER BY c.name ASC
");
$categories = $catStmt->fetchAll();
?>

<section class="mb-10">
    <div class="flex flex-wrap justify-center gap-3">
        <a href="/"
           class="px-6 py-2 rounded-full font-label text-sm transition-all <?= $activeSlug === 'all' ? 'bg-[#8c4a24] text-white' : 'bg-[#fadec5] text-[#564331] hover:bg-[#eee7db]' ?>">
            All
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="/?category=<?= htmlspecialchars($cat['slug']) ?>"
           class="px-6 py-2 rounded-full font-label text-sm transition-all <?= $activeSlug === $cat['slug'] ? 'bg-[#8c4a24] text-white' : 'bg-[#fadec5] text-[#564331] hover:bg-[#eee7db]' ?>">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="mt-6 border-t border-[#DDD0BC]"></div>
</section>
