<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    const status = document.getElementById('qrStatus');
    const readerEl = document.getElementById('qrReader');
    const scanBtn = document.getElementById('qrScanBtn');
    const applyBtn = document.getElementById('qrApplyBtn');
    const manual = document.getElementById('qrManual');
    const qrcodeInput = document.getElementById('qrcodeInput');
    const form = qrcodeInput.closest('form');
    let scanner = null;

    // QR payload key -> form field name
    const MAP = {
        item_name: 'item_name', name: 'item_name', item: 'item_name',
        category: 'category', brand: 'brand', color: 'color', size: 'size',
        type: 'type', unit: 'unit', location: 'location', receiver: 'receiver',
        status: 'status', supplier_id: 'supplier_id', purpose: 'purpose',
        department: 'department_unit', department_unit: 'department_unit',
        description: 'description', request_description: 'request_description',
        quantity: 'quantity', qty: 'quantity', quantity_requested: 'quantity_requested',
        unit_cost: 'unit_cost', cost: 'unit_cost', current_stock: 'current_stock',
        reorder_level: 'reorder_level', date_needed: 'date_needed',
        needed_date: 'date_needed', semester: 'semester', school_year: 'school_year',
    };

    function setStatus(msg, ok) {
        status.textContent = msg;
        status.classList.remove('hidden', 'text-red-500', 'text-emerald-600');
        status.classList.add(ok ? 'text-emerald-600' : 'text-red-500');
    }

    function setField(name, value) {
        const el = form.querySelector('[name="' + name + '"]');
        if (!el || value === undefined || value === null || value === '') return;
        if (el.tagName === 'SELECT') {
            const opt = Array.from(el.options).find((o) => o.value == value || o.text.trim().toLowerCase() === String(value).toLowerCase());
            if (opt) el.value = opt.value;
            return;
        }
        if (el.type === 'number' && isNaN(Number(value))) return;
        el.value = value;
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function applyCode(code) {
        code = code.trim();
        if (!code) return;
        qrcodeInput.value = code;
        let filled = 0;
        if (code.startsWith('{')) {
            try {
                const data = JSON.parse(code);
                Object.entries(data).forEach(([k, v]) => {
                    const field = MAP[k.toLowerCase()] || MAP[k.toLowerCase().replace(/\s+/g, '_')];
                    if (field) { setField(field, v); filled++; }
                });
            } catch (e) {
                setStatus('Invalid QR data format.', false);
                return;
            }
        } else {
            // Plain SKU/code: try to fill the item name if still empty
            const nameEl = form.querySelector('[name="item_name"]');
            if (nameEl && !nameEl.value) nameEl.value = code;
        }
        setStatus(filled ? 'QR applied — ' + filled + ' field(s) auto-filled.' : 'QR code attached to this request.', true);
    }

    applyBtn.addEventListener('click', () => applyCode(manual.value));
    manual.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); applyCode(manual.value); } });

    scanBtn.addEventListener('click', () => {
        if (scanner) {
            scanner.stop().then(() => { scanner.clear(); scanner = null; readerEl.classList.add('hidden'); scanBtn.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Scan with Camera'; });
            return;
        }
        scanner = new Html5Qrcode('qrReader');
        scanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 220, height: 220 } },
            (decodedText) => {
                applyCode(decodedText);
                scanner.stop().then(() => { scanner.clear(); scanner = null; readerEl.classList.add('hidden'); scanBtn.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Scan with Camera'; });
            },
            () => {}
        ).then(() => {
            readerEl.classList.remove('hidden');
            scanBtn.innerHTML = '<i class="fa-solid fa-stop mr-2"></i> Stop Camera';
        }).catch(() => setStatus('Camera access denied or unavailable — paste the code instead.', false));
    });
})();
</script>
