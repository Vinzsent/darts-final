<script>
(function () {
    const input = document.querySelector('form [name="item_name"]');
    if (!input) return;

    // Wrap for positioning
    const wrapper = document.createElement('div');
    wrapper.className = 'relative';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);

    const box = document.createElement('div');
    box.className = 'hidden absolute z-30 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto';
    wrapper.appendChild(box);

    let timer = null;
    let seq = 0;

    function hide() { box.classList.add('hidden'); }

    function esc(s) {
        return String(s).replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    function render(items) {
        if (!items.length) {
            box.innerHTML = '<div class="px-3 py-3 text-sm text-gray-500 flex items-center"><i class="fa-solid fa-circle-exclamation text-gray-400 mr-2"></i> No item found or the item is sold out.</div>';
            box.classList.remove('hidden');
            return;
        }
        box.innerHTML = items.map((it, i) => {
            const out = it.stock <= 0;
            return `<button type="button" data-i="${i}" class="w-full text-left px-3 py-2 hover:bg-emerald-50 transition flex items-center justify-between gap-2 ${out ? 'opacity-60' : ''}">
                <span class="min-w-0">
                    <span class="block text-sm font-medium text-gray-900 truncate">${esc(it.name)}</span>
                    <span class="block text-xs text-gray-500">${esc(it.category || '')} &middot; ${esc(it.source)}</span>
                </span>
                <span class="shrink-0 text-xs px-2 py-0.5 rounded-full ${out ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'}">
                    ${out ? 'Sold out' : it.stock + ' in stock'}
                </span>
            </button>`;
        }).join('');
        box.classList.remove('hidden');
        box.querySelectorAll('button[data-i]').forEach((btn) => {
            btn.addEventListener('mousedown', (e) => {
                e.preventDefault();
                const it = items[+btn.dataset.i];
                input.value = it.name;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                // Auto-fill related fields from the matched inventory/property record
                const form = input.closest('form');
                ['category', 'brand', 'color', 'size', 'type', 'unit'].forEach((field) => {
                    if (!it[field]) return;
                    const el = form.querySelector('[name="' + field + '"]');
                    if (!el) return;
                    const val = String(it[field]).trim();
                    if (val === '') return;
                    if (el.tagName === 'SELECT') {
                        const norm = (s) => String(s).trim().toLowerCase();
                        let opt = Array.from(el.options).find((o) => norm(o.value) === norm(val));
                        if (!opt) {
                            // No exact option: add the inventory value so the data is preserved
                            opt = document.createElement('option');
                            opt.value = val;
                            opt.textContent = val;
                            el.appendChild(opt);
                        }
                        el.value = opt.value;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        return;
                    }
                    el.value = it[field];
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                });
                hide();
            });
        });
    }

    function search(q) {
        const id = ++seq;
        fetch('{{ route("item-suggestions.index") }}?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        })
            .then((r) => r.json())
            .then((data) => { if (id === seq) render(data.items || []); })
            .catch(hide);
    }

    input.addEventListener('input', () => {
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 2) { hide(); return; }
        timer = setTimeout(() => search(q), 300);
    });

    input.addEventListener('blur', () => setTimeout(hide, 150));
})();
</script>
