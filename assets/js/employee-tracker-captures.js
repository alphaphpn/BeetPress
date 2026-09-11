(() => {
    const table = document.getElementById('listRecView');
    if (!table) return;
    const preview = document.createElement('div');
    preview.id = 'attendance-capture-preview';
    preview.setAttribute('role', 'tooltip');
    preview.style.cssText = 'position:fixed;z-index:1080;width:260px;max-width:calc(100vw - 16px);padding:10px;background:#fff;color:#212529;border:1px solid #aaa;border-radius:8px;box-shadow:0 4px 18px #0005;';
    preview.hidden = true;
    const label = document.createElement('div');
    label.style.cssText = 'font-size:13px;margin-bottom:6px;';
    const photo = document.createElement('img');
    photo.style.cssText = 'display:block;width:100%;height:180px;object-fit:contain;';
    const status = document.createElement('div');
    status.style.fontSize = '13px';
    preview.append(label, photo, status);
    document.body.append(preview);
    let active = null, timer;
    function hide() {
        clearTimeout(timer);
        if (active) active.removeAttribute('aria-describedby');
        active = null;
        preview.hidden = true;
        photo.removeAttribute('src');
    }
    function deferHide() { clearTimeout(timer); timer = setTimeout(hide, 150); }
    function show(button) {
        clearTimeout(timer);
        if (active === button) return;
        hide();
        active = button;
        button.setAttribute('aria-describedby', preview.id);
        label.textContent = button.dataset.captureLabel;
        photo.alt = 'Attendance capture: ' + button.dataset.captureLabel;
        photo.hidden = true;
        photo.style.display = 'none';
        status.textContent = button.dataset.captureUrl ? 'Loading capture…' : 'No capture available for this time.';
        photo.onload = () => { if (!active) return; photo.hidden = false; photo.style.display = 'block'; status.textContent = ''; };
        photo.onerror = () => { photo.style.display = 'none'; status.textContent = 'Capture image is unavailable.'; };
        preview.hidden = false;
        const rect = button.getBoundingClientRect();
        preview.style.left = Math.max(8, Math.min(rect.left, window.innerWidth - preview.offsetWidth - 8)) + 'px';
        preview.style.top = Math.max(8, rect.bottom + 260 < window.innerHeight ? rect.bottom + 6 : rect.top - 260) + 'px';
        if (button.dataset.captureUrl) photo.src = button.dataset.captureUrl;
    }
    table.addEventListener('mouseover', event => { const button = event.target.closest('[data-capture-url]'); if (button) show(button); });
    table.addEventListener('mouseout', event => { if (event.target.closest('[data-capture-url]')) deferHide(); });
    table.addEventListener('focusin', event => { const button = event.target.closest('[data-capture-url]'); if (button) show(button); });
    table.addEventListener('focusout', deferHide);
    preview.addEventListener('mouseenter', () => clearTimeout(timer));
    preview.addEventListener('mouseleave', deferHide);
    document.addEventListener('keydown', event => { if (event.key === 'Escape') hide(); });
    document.addEventListener('scroll', hide, true);
    window.addEventListener('resize', hide);
    table.addEventListener('click', hide);
})();
