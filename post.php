<?php
require_once __DIR__ . '/config/db.php';

$slug = trim($_GET['slug'] ?? '');

if (!$slug) {
    header('Location: /');
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name, c.slug AS category_slug
    FROM posts p
    JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? AND p.status = 'published'
    LIMIT 1
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    require __DIR__ . '/includes/head.php';
    echo '<main class="max-w-2xl mx-auto px-8 py-32 text-center">';
    echo '<h1 class="font-headline text-4xl text-[#8c4a24] mb-4">Post not found</h1>';
    echo '<a href="/" class="font-label text-[#8c4a24] underline">← Back to home</a>';
    echo '</main>';
    require __DIR__ . '/includes/footer.php';
    echo '</body></html>';
    exit;
}

// Increment view count
$pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$post['id']]);

// Recommendations: same category first, exclude current post, limit 3
$recStmt = $pdo->prepare("
    SELECT p.title, p.slug, p.cover_image, p.reading_time, p.published_at,
           c.name AS category_name, c.slug AS category_slug,
           (p.category_id = ?) AS same_category
    FROM posts p
    JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published' AND p.id != ?
    ORDER BY same_category DESC, p.published_at DESC
    LIMIT 3
");
$recStmt->execute([$post['category_id'], $post['id']]);
$recommendations = $recStmt->fetchAll();

$date     = date('F j, Y', strtotime($post['published_at']));
$readTime = (int)$post['reading_time'];

// ── SEO meta ─────────────────────────────────────────────────
// Title: "Post Title — Preeti Amble", truncated to 60 chars
$rawTitle   = $post['title'] . ' — Preeti Amble';
$seoTitle   = mb_strlen($rawTitle) <= 60 ? $rawTitle : mb_substr($post['title'], 0, 57 - mb_strlen(' — Preeti Amble') + mb_strlen(' — Preeti Amble')) . '… — Preeti Amble';
if (mb_strlen($seoTitle) > 60) $seoTitle = mb_substr($post['title'], 0, 45) . '… — Preeti Amble';

// Description: use excerpt, trim to 160 chars
$rawDesc        = strip_tags($post['excerpt'] ?? '');
$seoDescription = mb_strlen($rawDesc) <= 160 ? $rawDesc : mb_substr($rawDesc, 0, 157) . '…';

// Image for OG/Twitter
$seoImage = !empty($post['cover_image']) ? $post['cover_image'] : 'https://preetiamble.blog/assets/opengraph.png';

// Keywords: category + generic blog keywords
$seoKeywords = htmlspecialchars($post['category_name']) . ', personal essays, mindfulness, slow living, Preeti Amble, self growth, life stories';

// Article-specific structured data
$seoArticleDate    = date('c', strtotime($post['published_at']));
$seoArticleAuthor  = 'Preeti Amble';

/**
 * Renders block-editor JSON into clean HTML.
 */
function render_blocks(string $json): string
{
    $blocks = json_decode($json, true);
    if (!$blocks) return '<p>' . htmlspecialchars($json) . '</p>';
    if (isset($blocks['type'])) $blocks = [$blocks]; // single block

    $html = '';
    foreach ($blocks as $block) {
        $html .= render_block($block);
    }
    return $html;
}

function render_inline(array $content): string
{
    $html = '';
    if (isset($content['text'])) $content = [$content];
    foreach ($content as $node) {
        if (($node['type'] ?? '') !== 'text') continue;
        $text   = htmlspecialchars($node['text'] ?? '');
        $styles = $node['styles'] ?? [];
        if ($styles['bold']          ?? false) $text = "<strong>$text</strong>";
        if ($styles['italic']        ?? false) $text = "<em>$text</em>";
        if ($styles['underline']     ?? false) $text = "<u>$text</u>";
        if ($styles['strikethrough'] ?? false) $text = "<s>$text</s>";
        $html .= $text;
    }
    return $html;
}

function render_block(array $block): string
{
    $type    = $block['type']    ?? 'paragraph';
    $content = $block['content'] ?? [];
    $inner   = render_inline($content);

    return match ($type) {
        'paragraph'       => $inner ? "<p>$inner</p>\n" : "<br>\n",
        'heading'         => (function() use ($block, $inner) {
                                $level = min(6, max(1, (int)($block['props']['level'] ?? 2)));
                                return "<h{$level}>$inner</h{$level}>\n";
                             })(),
        'bulletListItem'  => "<li>$inner</li>\n",
        'numberedListItem'=> "<li>$inner</li>\n",
        'divider'         => "<hr>\n",
        'image'           => (function() use ($block) {
                                $url = $block['props']['url'] ?? '';
                                $alt = htmlspecialchars($block['props']['caption'] ?? '');
                                if (!$url) return '';
                                return "<figure><img src=\"" . htmlspecialchars($url) . "\" alt=\"$alt\" loading=\"lazy\"><figcaption>$alt</figcaption></figure>\n";
                             })(),
        default           => $inner ? "<p>$inner</p>\n" : '',
    };
}

require_once __DIR__ . '/includes/head.php';
?>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main>
    <!-- Post Header -->
    <header class="max-w-2xl mx-auto px-5 md:px-8 pt-10 md:pt-14 pb-8 md:pb-10 text-center">
        <a href="/?category=<?= htmlspecialchars($post['category_slug']) ?>"
           class="inline-block bg-[#fadec5] text-[#564331] px-3 py-1 rounded-full text-[10px] font-label font-semibold tracking-widest uppercase hover:bg-[#edd9c8] transition-colors mb-6">
            <?= htmlspecialchars($post['category_name']) ?>
        </a>
        <h1 class="font-headline italic text-2xl md:text-4xl lg:text-5xl text-[#1e1b14] leading-tight mb-6">
            <?= htmlspecialchars($post['title']) ?>
        </h1>
        <div class="flex items-center justify-center gap-3 text-[11px] font-label text-[#9c8878] tracking-widest uppercase">
            <span><?= strtoupper($date) ?></span>
            <span>·</span>
            <span><?= $readTime ?> MIN READ</span>
        </div>
    </header>

    <!-- Cover Image -->
    <?php if (!empty($post['cover_image'])): ?>
    <div class="max-w-3xl mx-auto px-5 md:px-8 mb-8 md:mb-12">
        <img
            src="<?= htmlspecialchars($post['cover_image']) ?>"
            alt="<?= htmlspecialchars($post['title']) ?>"
            class="w-full rounded-xl object-cover max-h-[480px] sepia-[10%] grayscale-[15%]"
        />
    </div>
    <?php endif; ?>

    <!-- Post Body -->
    <article class="max-w-2xl mx-auto px-5 md:px-8 pb-16 md:pb-24 font-body text-[#2c1e0f] text-[17px] md:text-[18px] leading-[1.9]
                    [&_p]:mb-6
                    [&_h1]:font-headline [&_h1]:text-3xl [&_h1]:text-[#1e1b14] [&_h1]:mt-10 [&_h1]:mb-4 [&_h1]:leading-snug
                    [&_h2]:font-headline [&_h2]:text-2xl [&_h2]:text-[#1e1b14] [&_h2]:mt-10 [&_h2]:mb-4 [&_h2]:leading-snug
                    [&_h3]:font-headline [&_h3]:text-xl  [&_h3]:text-[#1e1b14] [&_h3]:mt-8  [&_h3]:mb-3
                    [&_h4]:font-headline [&_h4]:text-lg  [&_h4]:text-[#1e1b14] [&_h4]:mt-8  [&_h4]:mb-3
                    [&_h5]:font-headline [&_h5]:text-base [&_h5]:text-[#1e1b14] [&_h5]:mt-6 [&_h5]:mb-2
                    [&_strong]:text-[#1e1b14] [&_strong]:font-semibold
                    [&_em]:italic [&_em]:text-[#53433c]
                    [&_hr]:my-10 [&_hr]:border-[#ddd0bc]
                    [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-6 [&_ul]:space-y-2
                    [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-6 [&_ol]:space-y-2
                    [&_li]:leading-relaxed
                    [&_figure]:my-10 [&_figure]:text-center
                    [&_figure_img]:rounded-lg [&_figure_img]:w-full [&_figure_img]:mx-auto
                    [&_figcaption]:text-sm [&_figcaption]:text-[#9c8878] [&_figcaption]:mt-3 [&_figcaption]:italic">
        <?= render_blocks($post['content']) ?>
    </article>

    <!-- Recommendations -->
    <?php if (!empty($recommendations)): ?>
    <section class="border-t border-[#ddd0bc] mt-4 pt-10 md:pt-14 pb-14 md:pb-20 px-5 md:px-8">
        <div class="max-w-[1140px] mx-auto">
            <p class="font-label text-xs tracking-widest uppercase text-[#9c8878] text-center mb-10">
                Continue Reading
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <?php foreach ($recommendations as $rec):
                    $recDate = date('M d, Y', strtotime($rec['published_at']));
                ?>
                <article class="flex flex-col bg-[#faf3e6] rounded-xl overflow-hidden group border border-[#d9c2b8]/30 transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_4px_24px_rgba(44,30,15,0.10)] hover:bg-[#fbf7ef]">
                    <a href="/<?= htmlspecialchars($rec['slug']) ?>" class="block aspect-video overflow-hidden bg-[#f1e8d6]">
                        <?php if (!empty($rec['cover_image'])): ?>
                        <img
                            src="<?= htmlspecialchars($rec['cover_image']) ?>"
                            alt="<?= htmlspecialchars($rec['title']) ?>"
                            class="w-full h-full object-cover grayscale-[20%] sepia-[10%] group-hover:scale-105 transition-transform duration-700"
                            loading="lazy"
                        />
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#f1e8d6] to-[#ddd0bc]">
                            <span class="material-symbols-outlined text-[#9c8878] text-4xl">auto_stories</span>
                        </div>
                        <?php endif; ?>
                    </a>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-3">
                            <span class="bg-[#fadec5] text-[#564331] px-3 py-1 rounded-full text-[10px] font-label font-semibold tracking-widest uppercase">
                                <?= htmlspecialchars($rec['category_name']) ?>
                            </span>
                        </div>
                        <h3 class="font-headline text-lg text-[#1e1b14] leading-snug mb-auto">
                            <a href="/<?= htmlspecialchars($rec['slug']) ?>" class="hover:text-[#8c4a24] transition-colors duration-200">
                                <?= htmlspecialchars($rec['title']) ?>
                            </a>
                        </h3>
                        <div class="pt-4 mt-4 border-t border-[#d9c2b8]/30 flex justify-between items-center text-[11px] font-label text-[#9c8878] tracking-wider uppercase">
                            <span><?= strtoupper($recDate) ?></span>
                            <span><?= (int)$rec['reading_time'] ?> MIN READ</span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="mt-12 text-center">
                <a href="/" class="font-label text-sm text-[#8c4a24] hover:text-[#564331] transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Back to all blogs
                </a>
            </div>
        </div>
    </section>
    <?php else: ?>
    <div class="max-w-2xl mx-auto px-5 md:px-8 pb-12 border-t border-[#ddd0bc] pt-8">
        <a href="/" class="font-label text-sm text-[#8c4a24] hover:text-[#564331] transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Back to all blogs
        </a>
    </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php if ($post): ?>
<script>
// ── Post-specific GA4 events ─────────────────────────────────
const POST_TITLE    = <?= json_encode($post['title']) ?>;
const POST_CATEGORY = <?= json_encode($post['category_name']) ?>;
const POST_SLUG     = <?= json_encode($post['slug']) ?>;
const READ_TIME     = <?= (int)($post['reading_time'] ?? 5) ?>;

// Post view (richer than default page_view)
gtag('event', 'post_view', {
    post_title:    POST_TITLE,
    post_category: POST_CATEGORY,
    post_slug:     POST_SLUG,
    read_time_min: READ_TIME
});

// Time-on-page milestones (1min, 3min, read_time)
[60, 180, READ_TIME * 60].filter((v, i, a) => a.indexOf(v) === i && v > 0).forEach(sec => {
    setTimeout(() => {
        gtag('event', 'time_on_post', {
            seconds:       sec,
            post_title:    POST_TITLE,
            post_category: POST_CATEGORY
        });
    }, sec * 1000);
});

// Post read completion — fires when user reaches the bottom of the article
(function() {
    const article = document.querySelector('article') || document.querySelector('main');
    if (!article) return;
    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            gtag('event', 'post_read_complete', {
                post_title:    POST_TITLE,
                post_category: POST_CATEGORY,
                post_slug:     POST_SLUG
            });
            observer.disconnect();
        }
    }, { threshold: 0.9 });
    // Observe the recommendations section as "end of post"
    const endMarker = document.querySelector('[data-end-marker]') || article.lastElementChild;
    if (endMarker) observer.observe(endMarker);
})();
</script>
<?php endif; ?>

</body>
</html>
