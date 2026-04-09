<?php if ($totalPages > 1): ?>
<?php
$categoryParam = ($filterSlug !== 'all') ? '&category=' . urlencode($filterSlug) : '';

function page_url(int $page, string $extra): string {
    return '/?page=' . $page . $extra;
}
?>
<nav class="flex justify-center items-center gap-2 md:gap-4 mb-16 md:mb-24 font-label text-sm font-medium">

    <?php if ($currentPage > 1): ?>
    <a class="text-[#53433c] hover:text-[#8c4a24] transition-colors flex items-center gap-1"
       href="<?= page_url($currentPage - 1, $categoryParam) ?>">
        <span class="material-symbols-outlined text-lg">chevron_left</span>
        Previous
    </a>
    <?php else: ?>
    <span class="text-[#9c8878] flex items-center gap-1 cursor-not-allowed select-none">
        <span class="material-symbols-outlined text-lg">chevron_left</span>
        Previous
    </span>
    <?php endif; ?>

    <div class="flex gap-2">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?= page_url($i, $categoryParam) ?>"
           class="w-10 h-10 flex items-center justify-center rounded-full transition-colors
                  <?= $i === $currentPage
                      ? 'bg-[#8c4a24] text-white'
                      : 'text-[#53433c] hover:bg-[#eee7db]' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>

    <?php if ($currentPage < $totalPages): ?>
    <a class="text-[#53433c] hover:text-[#8c4a24] transition-colors flex items-center gap-1"
       href="<?= page_url($currentPage + 1, $categoryParam) ?>">
        Next
        <span class="material-symbols-outlined text-lg">chevron_right</span>
    </a>
    <?php else: ?>
    <span class="text-[#9c8878] flex items-center gap-1 cursor-not-allowed select-none">
        Next
        <span class="material-symbols-outlined text-lg">chevron_right</span>
    </span>
    <?php endif; ?>

</nav>
<?php endif; ?>
