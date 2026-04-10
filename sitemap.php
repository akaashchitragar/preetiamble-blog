<?php
require_once __DIR__ . '/config/db.php';

$posts = $pdo->query("
    SELECT slug, published_at FROM posts
    WHERE status = 'published'
    ORDER BY published_at DESC
")->fetchAll();

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://preetiamble.blog/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <?php foreach ($posts as $p): ?>
    <url>
        <loc>https://preetiamble.blog/<?= htmlspecialchars($p['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($p['published_at'])) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>
</urlset>
