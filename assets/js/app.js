// Site-wide JavaScript.

(function () {
    const input = document.getElementById('attachment');
    const panel = document.getElementById('attachment-preview');
    const title = document.getElementById('attachment-preview-title');
    const meta = document.getElementById('attachment-preview-meta');
    const body = document.getElementById('attachment-preview-body');
    const clearButton = document.getElementById('attachment-preview-clear');

    if (!input || !panel || !title || !meta || !body || !clearButton) {
        return;
    }

    const IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg'];
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    let objectUrl = null;

    function releaseObjectUrl() {
        if (objectUrl !== null) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }
    }

    function formatSize(bytes) {
        if (bytes < 1024) {
            return bytes + ' B';
        }

        if (bytes < 1024 * 1024) {
            return (bytes / 1024).toFixed(1) + ' KB';
        }

        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function renderPreview() {
        releaseObjectUrl();

        const file = input.files && input.files[0];
        if (!file) {
            body.replaceChildren();
            panel.hidden = true;
            return;
        }

        const extension = file.name.split('.').pop().toLowerCase();
        const overLimit = file.size > MAX_FILE_SIZE;

        title.textContent = file.name;
        meta.textContent = formatSize(file.size) + (overLimit ? ' · Over 5 MB' : '');

        if (IMAGE_EXTENSIONS.includes(extension)) {
            objectUrl = URL.createObjectURL(file);

            const image = document.createElement('img');
            image.src = objectUrl;
            image.alt = 'Preview of ' + file.name;
            body.replaceChildren(image);
        } else if (extension === 'pdf') {
            objectUrl = URL.createObjectURL(file);

            const frame = document.createElement('iframe');
            frame.src = objectUrl;
            frame.title = 'Preview of ' + file.name;
            frame.setAttribute('loading', 'lazy');
            body.replaceChildren(frame);
        } else {
            const message = document.createElement('p');
            message.className = 'preview-unavailable';
            message.textContent = 'Preview is not available for this file type. It will still be uploaded as a supporting file.';
            body.replaceChildren(message);
        }

        panel.hidden = false;
    }

    input.addEventListener('change', renderPreview);

    clearButton.addEventListener('click', function () {
        input.value = '';
        renderPreview();
        input.focus();
    });

    window.addEventListener('beforeunload', releaseObjectUrl);
})();
