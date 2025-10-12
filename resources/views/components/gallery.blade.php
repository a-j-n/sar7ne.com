<div id="lightbox" class="hidden fixed inset-0 z-50 bg-black/90 text-white select-none">
    <button type="button" id="lightboxClose" class="absolute top-4 right-4 p-2 rounded-full bg-white/10 hover:bg-white/20 focus:outline-none">✕</button>
    <button type="button" id="lightboxPrev" class="absolute left-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 hover:bg-white/20">‹</button>
    <button type="button" id="lightboxNext" class="absolute right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 hover:bg-white/20">›</button>
    <div id="lightboxStage" class="w-full h-full flex items-center justify-center p-4">
        <img id="lightboxImage" src="" alt="" class="max-w-full max-h-full object-contain" />
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('lightbox');
    const imgEl = document.getElementById('lightboxImage');
    const btnClose = document.getElementById('lightboxClose');
    const btnPrev = document.getElementById('lightboxPrev');
    const btnNext = document.getElementById('lightboxNext');

    let currentGroup = [];
    let currentIndex = 0;

    function openAt(index) {
        currentIndex = index;
        const src = currentGroup[currentIndex];
        imgEl.src = src;
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        // Preload neighbors
        const prev = new Image(); prev.src = currentGroup[(currentIndex - 1 + currentGroup.length) % currentGroup.length];
        const next = new Image(); next.src = currentGroup[(currentIndex + 1) % currentGroup.length];
    }

    function close() {
        overlay.classList.add('hidden');
        imgEl.src = '';
        document.body.classList.remove('overflow-hidden');
    }

    function prev() { openAt((currentIndex - 1 + currentGroup.length) % currentGroup.length); }
    function next() { openAt((currentIndex + 1) % currentGroup.length); }

    btnClose.addEventListener('click', close);
    btnPrev.addEventListener('click', prev);
    btnNext.addEventListener('click', next);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
    document.addEventListener('keydown', (e) => {
        if (overlay.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') prev();
        if (e.key === 'ArrowRight') next();
    });

    // Delegate clicks from any element with data-gallery-src
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-gallery-src]');
        if (!trigger) return;
        e.preventDefault();
        const groupName = trigger.getAttribute('data-gallery-group') || 'default';
        const groupEls = Array.from(document.querySelectorAll(`[data-gallery-src][data-gallery-group="${groupName}"]`));
        currentGroup = groupEls.map(el => el.getAttribute('data-gallery-src'));
        const clickedSrc = trigger.getAttribute('data-gallery-src');
        const idx = currentGroup.indexOf(clickedSrc);
        openAt(Math.max(0, idx));
    });
});
</script>

