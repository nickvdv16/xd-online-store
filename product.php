<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'classes/Product.php';

if (!isset($_GET['id'])) {
    die('Geen product geselecteerd.');
}

$productModel = new Product();
$product = $productModel->getById((int)$_GET['id']);

if (!$product) {
    die('Product niet gevonden.');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?></title>
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>

<header class="header">
    <div class="logo">Logo</div>
    <div class="header-right">
        <span class="icon">👤</span>
        <span class="icon">🛒</span>
    </div>
</header>

<main class="product-page">

    <section class="product-top">

        <div class="product-image-wrapper">
            <img src="assets/images/<?= htmlspecialchars($product['image']) ?>"
                 alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

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

            <div class="quantity">
                <label>Aantal</label>
                <div class="quantity-controls">
                    <button>-</button>
                    <span>1</span>
                    <button>+</button>
                </div>
            </div>

            <button class="add-to-cart">In winkelwagen</button>
        </div>

    </section>

    <section class="product-description">
        <h2>Productomschrijving</h2>
        <div class="description-box">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Geen beschrijving beschikbaar.')) ?>
        </div>
    </section>

    <section class="reviews">
        <h2>Beoordelingen van klanten</h2>

        <div class="review">
            <strong>Lotte S.</strong> – 22 mei 2024  
            <p>ETB’s zijn altijd een goede keuze.</p>
        </div>

        <div class="review">
            <strong>Umut</strong> – 14 augustus 2024  
            <p>Snelle levering en goed verpakt.</p>
        </div>

    </section>

</main>

<footer class="footer">
    © Online Store Werkt
</footer>

</body>
</html>