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

    const forms = document.querySelectorAll(".confirm-delete");
    if (forms.length > 0) {
        for (const form of forms) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();

                if (confirm("Are you sure you want delete this resource?")) {
                    e.target.submit();
                }
            })
        }
    }
});
