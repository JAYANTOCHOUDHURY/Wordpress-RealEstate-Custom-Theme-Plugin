document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (e) {
        if (e.target.closest('.wishlist-icon-btn')) {
            e.preventDefault();
            var btn = e.target.closest('.wishlist-icon-btn'); // Always the button itself
            var propertyId = btn.getAttribute('data-property');
            var actionType = btn.getAttribute('data-action');
            var nonce = btn.getAttribute('data-nonce');

            btn.disabled = true;

            fetch(wishlist_ajax.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=toggle_wishlist_property&property_id=' + encodeURIComponent(propertyId)
                    + '&action_type=' + encodeURIComponent(actionType)
                    + '&nonce=' + encodeURIComponent(nonce)
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.success) {
                    // Update icon only for THIS button
                    var icon = btn.querySelector('i');
                    if (data.data.action === 'add') {
                        icon.className = 'fa-regular fa-heart';
                        btn.setAttribute('aria-label', 'Add to Wish List');
                    } else {
                        icon.className = 'fa-solid fa-heart';
                        btn.setAttribute('aria-label', 'Remove from Wish List');
                    }
                    btn.setAttribute('data-action', data.data.action);
                } else {
                    // Optionally show error state
                }
                btn.disabled = false;
            })
            .catch(() => {
                btn.disabled = false;
            });
        }
    });
});
