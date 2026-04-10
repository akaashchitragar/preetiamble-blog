<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" type="image/x-icon" href="/assets/favicon.ico"/>
<title><?= htmlspecialchars($seoTitle ?? 'Preeti Amble — Personal Essays on Life & Mindfulness') ?></title>
<meta name="description" content="<?= htmlspecialchars($seoDescription ?? 'Real, honest essays on mindfulness, self-growth, relationships, and the art of living slowly. Written by Preeti Amble. New stories every week.') ?>"/>

<!-- Robots & indexing -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
<meta name="googlebot" content="index, follow"/>

<!-- AI crawlers — allow all -->
<meta name="GPTBot" content="index, follow"/>
<meta name="ChatGPT-User" content="index, follow"/>
<meta name="anthropic-ai" content="index, follow"/>
<meta name="ClaudeBot" content="index, follow"/>
<meta name="CCBot" content="index, follow"/>
<meta name="PerplexityBot" content="index, follow"/>
<meta name="cohere-ai" content="index, follow"/>

<!-- Keywords -->
<meta name="keywords" content="<?= htmlspecialchars($seoKeywords ?? 'personal essays, mindfulness, self growth, slow living, life stories, Preeti Amble, relationships, mental wellness') ?>"/>

<!-- Author & language -->
<meta name="author" content="Preeti Amble"/>
<meta name="language" content="en"/>
<meta http-equiv="content-language" content="en"/>

<!-- Open Graph -->
<meta property="og:type"        content="<?= isset($post) ? 'article' : 'website' ?>"/>
<meta property="og:site_name"   content="Preeti Amble"/>
<meta property="og:title"       content="<?= htmlspecialchars($seoTitle ?? 'Preeti Amble — Personal Essays on Life & Mindfulness') ?>"/>
<meta property="og:description" content="<?= htmlspecialchars($seoDescription ?? 'Real, honest essays on mindfulness, self-growth, relationships, and the art of living slowly. Written by Preeti Amble. New stories every week.') ?>"/>
<meta property="og:url"         content="https://preetiamble.blog<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?>"/>
<?php if (!empty($seoImage)): ?>
<meta property="og:image"       content="<?= htmlspecialchars($seoImage) ?>"/>
<meta property="og:image:width"  content="1200"/>
<meta property="og:image:height" content="630"/>
<?php endif; ?>

<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image"/>
<meta name="twitter:title"       content="<?= htmlspecialchars($seoTitle ?? 'Preeti Amble — Personal Essays on Life & Mindfulness') ?>"/>
<meta name="twitter:description" content="<?= htmlspecialchars($seoDescription ?? 'Real, honest essays on mindfulness, self-growth, relationships, and the art of living slowly. Written by Preeti Amble. New stories every week.') ?>"/>
<?php if (!empty($seoImage)): ?>
<meta name="twitter:image"       content="<?= htmlspecialchars($seoImage) ?>"/>
<?php endif; ?>

<!-- Canonical -->
<link rel="canonical" href="https://preetiamble.blog<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'] ?? '/', '?')) ?>"/>

<?php if (isset($post) && !empty($post['id'])): ?>
<!-- Article OG tags -->
<meta property="article:published_time" content="<?= htmlspecialchars($seoArticleDate ?? '') ?>"/>
<meta property="article:author"         content="Preeti Amble"/>
<meta property="article:section"        content="<?= htmlspecialchars($post['category_name'] ?? '') ?>"/>

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": <?= json_encode(mb_substr($post['title'], 0, 110)) ?>,
    "description": <?= json_encode($seoDescription ?? '') ?>,
    "datePublished": <?= json_encode($seoArticleDate ?? '') ?>,
    "author": { "@type": "Person", "name": "Preeti Amble", "url": "https://preetiamble.blog" },
    "publisher": { "@type": "Person", "name": "Preeti Amble", "url": "https://preetiamble.blog" },
    "url": "https://preetiamble.blog/<?= htmlspecialchars($post['slug']) ?>",
    "mainEntityOfPage": "https://preetiamble.blog/<?= htmlspecialchars($post['slug']) ?>"
    <?php if (!empty($post['cover_image'])): ?>
    ,"image": <?= json_encode($post['cover_image']) ?>
    <?php endif; ?>
}
</script>
<?php else: ?>
<!-- JSON-LD for homepage -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Preeti Amble",
    "description": "Real, honest essays on mindfulness, self-growth, relationships, and the art of living slowly.",
    "url": "https://preetiamble.blog",
    "author": { "@type": "Person", "name": "Preeti Amble" }
}
</script>
<?php endif; ?>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary-fixed": "#ffdbca",
                    "tertiary": "#68594c",
                    "tertiary-fixed": "#f3dfce",
                    "on-primary-fixed-variant": "#723611",
                    "surface-container": "#f4ede0",
                    "background": "#fff9ee",
                    "surface-tint": "#8f4d26",
                    "secondary-fixed": "#fadec5",
                    "on-surface": "#1e1b14",
                    "on-primary-fixed": "#341100",
                    "on-error": "#ffffff",
                    "on-background": "#1e1b14",
                    "on-primary-container": "#fffbff",
                    "primary": "#8c4a24",
                    "error-container": "#ffdad6",
                    "surface-container-high": "#eee7db",
                    "tertiary-container": "#817264",
                    "on-tertiary": "#ffffff",
                    "surface-container-highest": "#e8e2d5",
                    "secondary-container": "#fadec5",
                    "surface-container-lowest": "#ffffff",
                    "on-error-container": "#93000a",
                    "on-secondary-container": "#75614d",
                    "inverse-surface": "#333028",
                    "on-secondary": "#ffffff",
                    "on-secondary-fixed": "#27190a",
                    "on-primary": "#ffffff",
                    "surface-container-low": "#faf3e6",
                    "inverse-on-surface": "#f7f0e3",
                    "secondary": "#6f5b47",
                    "on-tertiary-fixed": "#241a0f",
                    "on-surface-variant": "#53433c",
                    "tertiary-fixed-dim": "#d6c3b3",
                    "primary-fixed-dim": "#ffb690",
                    "surface-variant": "#e8e2d5",
                    "on-tertiary-fixed-variant": "#524438",
                    "outline": "#86736a",
                    "surface-dim": "#e0d9cd",
                    "on-tertiary-container": "#fffbff",
                    "primary-container": "#aa623a",
                    "on-secondary-fixed-variant": "#564331",
                    "inverse-primary": "#ffb690",
                    "surface-bright": "#fff9ee",
                    "outline-variant": "#d9c2b8",
                    "error": "#ba1a1a",
                    "secondary-fixed-dim": "#dcc2aa",
                    "surface": "#fff9ee"
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
                fontFamily: {
                    "headline": ["Newsreader", "serif"],
                    "body": ["Noto Serif", "serif"],
                    "label": ["Inter", "sans-serif"]
                }
            }
        }
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        vertical-align: middle;
    }
    body {
        font-family: 'Noto Serif', serif;
        background-color: #fff9ee;
        color: #1e1b14;
    }
    .editorial-shadow {
        box-shadow: 0 12px 40px rgba(44, 30, 15, 0.06);
    }
    .hover\:editorial-shadow:hover {
        box-shadow: 0 12px 40px rgba(44, 30, 15, 0.10);
    }
</style>
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MXQNVLW07T"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-MXQNVLW07T', {
    send_page_view: true,
    cookie_flags: 'SameSite=None;Secure'
});

// ── Scroll depth tracking ────────────────────────────────────
(function() {
    const marks = [25, 50, 75, 90];
    const fired = new Set();
    window.addEventListener('scroll', function() {
        const pct = Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
        marks.forEach(m => {
            if (pct >= m && !fired.has(m)) {
                fired.add(m);
                gtag('event', 'scroll_depth', { depth_percentage: m });
            }
        });
    }, { passive: true });
})();

// ── Outbound link tracking ───────────────────────────────────
document.addEventListener('click', function(e) {
    const a = e.target.closest('a');
    if (a && a.hostname && a.hostname !== location.hostname) {
        gtag('event', 'outbound_click', { url: a.href, text: a.innerText?.trim().substring(0, 100) });
    }
});
</script>
</head>
<body class="bg-background text-on-background">
