require('./bootstrap');

document.addEventListener('DOMContentLoaded', (event) => {
    const pushForm = document.getElementById('push-form');

    if (!pushForm) {
        return;
    }

    const urlInput = pushForm.querySelector('input#url');
    const deviceSelect = pushForm.querySelector('select#device_id');

    document.querySelectorAll('a.push-again-link').forEach((anchor) => {
        anchor.addEventListener('click', (click) => {
            click.preventDefault();

            urlInput.value = anchor.dataset.url;
            deviceSelect.value = anchor.dataset.device;
            pushForm.submit();
        })
    })
});
