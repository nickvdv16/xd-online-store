const decreaseBtn = document.getElementById('decrease');
const increaseBtn = document.getElementById('increase');
const qtySpan = document.getElementById('qty');
const qtyInput = document.getElementById('quantityInput');

if (qtyInput) {
    let quantity = parseInt(qtyInput.value);
    const maxStock = parseInt(qtyInput.dataset.max);

    increaseBtn.addEventListener('click', () => {
        if (quantity < maxStock) {
            quantity++;
            update();
        }
    });

    decreaseBtn.addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            update();
        }
    });

    function update() {
        qtySpan.textContent = quantity;
        qtyInput.value = quantity;

        // Disable buttons netjes
        decreaseBtn.disabled = quantity <= 1;
        increaseBtn.disabled = quantity >= maxStock;
    }

    // Init state
    update();
}