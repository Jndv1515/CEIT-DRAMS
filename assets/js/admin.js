// Admin queue decision confirmations.

(function () {
    const dialog = document.getElementById('decision-dialog');

    if (!dialog) {
        return;
    }

    const title = document.getElementById('decision-dialog-title');
    const description = document.getElementById('decision-dialog-description');
    const reasonField = document.getElementById('decision-dialog-reason-field');
    const reason = document.getElementById('decision-reason');
    const reasonError = document.getElementById('decision-dialog-error');
    const cancelButton = document.getElementById('decision-cancel');
    const confirmButton = document.getElementById('decision-confirm');

    let activeForm = null;
    let requiresReason = false;

    function requestLabel(form) {
        const id = form.querySelector('[name="id"]');
        const documentName = form.querySelector('[name="document"]');
        return (documentName ? documentName.value + ' ' : '') + (id ? '(' + id.value + ')' : '').trim();
    }

    function submit(form) {
        const reasonInput = form.querySelector('[data-reason]');

        if (reasonInput) {
            reasonInput.value = requiresReason ? reason.value.trim() : '';
        }

        form.submit();
    }

    if (typeof dialog.showModal !== 'function') {
        // Fallback for browsers without native <dialog> support.
        document.querySelectorAll('[data-decision-form]').forEach(function (form) {
            const button = form.querySelector('[data-confirm]');

            if (!button) {
                return;
            }

            button.addEventListener('click', function (event) {
                const status = form.querySelector('[name="status"]');
                const isRejection = status && status.value === 'rejected';

                if (isRejection) {
                    const answer = window.prompt('Reason for rejecting ' + requestLabel(form) + ':');

                    if (answer === null || answer.trim().length < 10) {
                        event.preventDefault();
                        return;
                    }

                    const reasonInput = form.querySelector('[data-reason]');

                    if (reasonInput) {
                        reasonInput.value = answer.trim();
                    }
                } else if (!window.confirm('Continue with this action for ' + requestLabel(form) + '?')) {
                    event.preventDefault();
                }
            });
        });

        return;
    }

    function open(form) {
        activeForm = form;

        const status = form.querySelector('[name="status"]');
        requiresReason = Boolean(status && status.value === 'rejected');
        const label = requestLabel(form);

        title.textContent = requiresReason ? 'Reject request' : 'Reset request';
        description.textContent = requiresReason
            ? 'Request ' + label + ' will be marked as Rejected. You can undo this later with Reset.'
            : 'Request ' + label + ' will be returned to Pending.';

        reasonField.hidden = !requiresReason;
        reason.value = '';
        reasonError.hidden = true;

        dialog.showModal();
        (requiresReason ? reason : confirmButton).focus();
    }

    document.querySelectorAll('[data-decision-form]').forEach(function (form) {
        const button = form.querySelector('[data-confirm]');

        if (!button) {
            return;
        }

        button.addEventListener('click', function (event) {
            event.preventDefault();
            open(form);
        });
    });

    cancelButton.addEventListener('click', function () {
        dialog.close();
    });

    confirmButton.addEventListener('click', function () {
        if (requiresReason && reason.value.trim().length < 10) {
            reasonError.hidden = false;
            reason.focus();
            return;
        }

        if (activeForm) {
            submit(activeForm);
        }
    });

    dialog.addEventListener('close', function () {
        if (activeForm) {
            const button = activeForm.querySelector('[data-confirm]');

            if (button) {
                button.focus();
            }

            activeForm = null;
        }
    });
})();
