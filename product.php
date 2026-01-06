<?php
session_start();
require_once 'classes/Product.php';

/* =========================
   PRODUCT OPHALEN
========================= */
if (!isset($_GET['id'])) {
    die('Geen product geselecteerd.');
}

$productModel = new Product();
$product = $productModel->getById((int)$_GET['id']);

if (!$product) {
    die('Product niet gevonden.');
}

/* =========================
   TOEVOEGEN AAN WINKELWAGEN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    $id = (int)$product['id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($qty < 1) $qty = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'title' => $product['title'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $qty
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?></title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- HEADER -->
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- PRODUCT -->
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- =========================
     PAGINA
========================= -->
<main class="product-page">

    <section class="product-top">

        <!-- AFBEELDING -->
        <div class="product-image-wrapper">
            <img src="assets/images/<?= htmlspecialchars($product['image']) ?>"
                 alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

        <!-- INFO -->
        <div class="product-info">
            <h1><?= htmlspecialchars($product['title']) ?></h1>

            <p class="price">
                €<?= number_format($product['price'], 2, ',', '.') ?>
            </p>

            <?php if ($product['stock'] > 0): ?>
                <p class="stock in-stock">✔ In stock</p>
            <?php else: ?>
                <p class="stock out-of-stock">✖ Out of stock</p>
            <?php endif; ?>

            <!-- TOEVOEGEN AAN CART -->
            <form method="post" class="add-to-cart-form">

                <div class="quantity">
                    <button type="button" id="decrease">−</button>
                    <span id="qty">1</span>
                    <button type="button" id="increase">+</button>
                </div>

                <input type="hidden" name="quantity" id="quantityInput" value="1">

                <button type="submit" name="add_to_cart" class="add-to-cart">
                    In winkelwagen
                </button>

            </form>
        </div>

    </section>

    <!-- OMSCHRIJVING -->
    <section class="product-description">
        <h2>Productomschrijving</h2>
        <div class="description-box">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Geen beschrijving beschikbaar.')) ?>
        </div>
    </section>

</main>

<footer class="footer">
    © Online Store
</footer>

<!-- JS -->
<script src="assets/js/product.js"></script>
</body>
</html>