// Document catalog preview dialog.

(function () {
    const dialog = document.getElementById('document-preview');

    if (!dialog) {
        return;
    }

    const buttons = document.querySelectorAll('[data-preview]');
    const title = document.getElementById('preview-title');
    const category = document.getElementById('preview-category');
    const body = document.getElementById('preview-body');
    const meta = document.getElementById('preview-meta');
    const download = document.getElementById('preview-download');
    const closeButton = document.getElementById('preview-close');

    let opener = null;

    if (typeof dialog.showModal !== 'function') {
        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                window.location.href = button.dataset.src;
            });
        });
        return;
    }

    function previewElement(button, source) {
        const format = (button.dataset.format || '').toUpperCase();

        if (format === 'PDF') {
            const frame = document.createElement('iframe');
            frame.src = source;
            frame.title = 'Preview of ' + (button.dataset.title || 'document');
            frame.setAttribute('loading', 'lazy');
            return frame;
        }

        if (['PNG', 'JPG', 'JPEG'].includes(format)) {
            const image = document.createElement('img');
            image.src = source;
            image.alt = 'Preview of ' + (button.dataset.title || 'document');
            return image;
        }

        const message = document.createElement('p');
        message.className = 'preview-unavailable';
        message.textContent = 'Inline preview is not available for this file type. You can still download the document.';
        return message;
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            opener = button;
            title.textContent = button.dataset.title || 'Document';
            category.textContent = button.dataset.category || '';
            meta.textContent = [button.dataset.format, button.dataset.size]
                .filter(Boolean)
                .join(' · ');
            download.href = button.dataset.download || '#';
            body.replaceChildren(previewElement(button, button.dataset.src));
            dialog.showModal();
            closeButton.focus();
        });
    });

    closeButton.addEventListener('click', function () {
        dialog.close();
    });

    dialog.addEventListener('close', function () {
        body.replaceChildren();

        if (opener) {
            opener.focus();
            opener = null;
        }
    });
})();
