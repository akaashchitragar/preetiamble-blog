<?php
/**
 * Pulls plain text from a BlockNote/block-editor JSON string.
 * Works for both a single block object and an array of blocks.
 */
function excerpt_plain_text(string $json, int $maxChars = 160): string
{
    $data = json_decode($json, true);
    if (!$data) return '';

    // Normalise: wrap single block in array
    if (isset($data['type'])) $data = [$data];

    $text = '';
    foreach ($data as $block) {
        $content = $block['content'] ?? [];
        // content can be an array of inline nodes or a single node
        if (isset($content['text'])) $content = [$content];
        foreach ($content as $inline) {
            if (($inline['type'] ?? '') === 'text') {
                $text .= $inline['text'] ?? '';
            }
        }
        if (strlen($text) >= $maxChars) break;
    }

    $text = trim(preg_replace('/\s+/', ' ', $text));
    if (strlen($text) > $maxChars) {
        $text = rtrim(substr($text, 0, $maxChars)) . '…';
    }
    return $text;
}

// ── Query ────────────────────────────────────────────────────
$postsPerPage = 6;
$currentPage  = max(1, (int)($_GET['page'] ?? 1));
$offset       = ($currentPage - 1) * $postsPerPage;
$filterSlug   = $_GET['category'] ?? 'all';

// Total count for pagination
if ($filterSlug === 'all') {
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM posts WHERE status = 'published'
    ");
    $countStmt->execute();
} else {
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM posts p
        JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'published' AND c.slug = ?
    ");
    $countStmt->execute([$filterSlug]);
}
$totalPosts = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($totalPosts / $postsPerPage);

// Posts for current page
if ($filterSlug === 'all') {
    $stmt = $pdo->prepare("
        SELECT p.title, p.slug, p.excerpt, p.cover_image,
               p.reading_time, p.published_at,
               c.name AS category_name, c.slug AS category_slug
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'published'
        ORDER BY p.published_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$postsPerPage, $offset]);
} else {
    $stmt = $pdo->prepare("
        SELECT p.title, p.slug, p.excerpt, p.cover_image,
               p.reading_time, p.published_at,
               c.name AS category_name, c.slug AS category_slug
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'published' AND c.slug = ?
        ORDER BY p.published_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$filterSlug, $postsPerPage, $offset]);
}
$posts = $stmt->fetchAll();
?>

<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-12 mb-20 max-w-[1140px] mx-auto">
    <?php if (empty($posts)): ?>
    <div class="col-span-3 text-center py-24 text-[#9C8878] font-body text-lg">
        No posts found in this category yet.
    </div>
    <?php endif; ?>

    <?php foreach ($posts as $post):
        $excerptText = excerpt_plain_text($post['excerpt']);
        $date        = date('M d, Y', strtotime($post['published_at']));
        $readTime    = (int)$post['reading_time'] . ' MIN READ';
        $hasCover    = !empty($post['cover_image']);
    ?>
    <article class="flex flex-col bg-[#faf3e6] rounded-xl overflow-hidden group transition-all duration-200 border border-[#d9c2b8]/30 hover:-translate-y-1 hover:shadow-[0_4px_24px_rgba(44,30,15,0.10)] hover:bg-[#fbf7ef]">

        <a href="/<?= htmlspecialchars($post['slug']) ?>" class="block aspect-video overflow-hidden bg-[#f1e8d6]">
            <?php if ($hasCover): ?>
            <img
                class="w-full h-full object-cover grayscale-[20%] sepia-[10%] group-hover:scale-105 transition-transform duration-700"
                src="<?= htmlspecialchars($post['cover_image']) ?>"
                alt="<?= htmlspecialchars($post['title']) ?>"
                loading="lazy"
            />
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#f1e8d6] to-[#ddd0bc]">
                <span class="material-symbols-outlined text-[#9c8878] text-5xl">auto_stories</span>
            </div>
            <?php endif; ?>
        </a>

        <div class="p-5 md:p-8 flex flex-col flex-grow">
            <div class="mb-4">
                <a href="/?category=<?= htmlspecialchars($post['category_slug']) ?>" class="bg-[#fadec5] text-[#564331] px-3 py-1 rounded-full text-[10px] font-label font-semibold tracking-widest uppercase hover:bg-[#edd9c8] transition-colors">
                    <?= htmlspecialchars($post['category_name']) ?>
                </a>
            </div>
            <h3 class="font-headline text-2xl text-[#1e1b14] mb-3 leading-snug">
                <a href="/<?= htmlspecialchars($post['slug']) ?>" class="hover:text-[#8c4a24] transition-colors duration-200">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
            </h3>
            <p class="font-body text-[#53433c] text-sm leading-[1.8] mb-6 flex-grow line-clamp-3">
                <?= htmlspecialchars($excerptText) ?>
            </p>
            <div class="pt-6 border-t border-[#d9c2b8]/30 flex justify-between items-center text-[11px] font-label text-[#53433c] tracking-wider uppercase">
                <span><?= strtoupper($date) ?></span>
                <span><?= $readTime ?></span>
            </div>
        </div>

    </article>
    <?php endforeach; ?>
</section>
