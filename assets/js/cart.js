document.querySelectorAll('.increase-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const stock = parseInt(btn.dataset.stock, 10);
        const quantitySpan = btn.closest('.cart-quantity').querySelector('span');
        const currentQty = parseInt(quantitySpan.textContent, 10);

        if (currentQty >= stock) {
            e.preventDefault();
            alert('Maximale voorraad bereikt');
        }
    });
});