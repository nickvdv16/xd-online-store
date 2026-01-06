let qty = 1;

const qtyDisplay = document.getElementById('qty');
const qtyInput = document.getElementById('quantityInput');
const increaseBtn = document.getElementById('increase');
const decreaseBtn = document.getElementById('decrease');

increaseBtn.addEventListener('click', () => {
    qty++;
    qtyDisplay.textContent = qty;
    qtyInput.value = qty;
});

decreaseBtn.addEventListener('click', () => {
    if (qty > 1) {
        qty--;
        qtyDisplay.textContent = qty;
        qtyInput.value = qty;
    }
});