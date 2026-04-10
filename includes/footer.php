<script>
// ── Homepage interactions ────────────────────────────────────
document.addEventListener('click', function(e) {
    // Blog card click
    const card = e.target.closest('article a, [data-post-slug]');
    if (card) {
        const slug  = card.closest('[data-post-slug]')?.dataset.postSlug || card.pathname?.replace('/', '');
        const title = card.closest('article')?.querySelector('h2, h3')?.innerText?.trim();
        if (slug) gtag('event', 'post_card_click', { post_slug: slug, post_title: title || '' });
    }

    // Category filter click
    const catBtn = e.target.closest('[data-category]');
    if (catBtn) {
        gtag('event', 'category_filter_click', { category: catBtn.dataset.category });
    }
});
</script>

<footer class="bg-[#f1e8d6] py-8">
    <div class="max-w-7xl mx-auto px-5 md:px-8 flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4">

        <span class="font-headline italic text-[#2C1E0F] text-lg">Preeti Amble</span>

        <p class="font-label text-xs text-[#9c8878]">
            &copy; <?= date('Y') ?> Preeti Amble. All rights reserved.
        </p>

    </div>
</footer>
