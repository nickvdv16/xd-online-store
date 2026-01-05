<?php
require_once 'classes/Product.php';

$productModel = new Product();

$articleA = $productModel->getByCategory(1, 6);
$articleB = $productModel->getByCategory(2, 6);
$articleC = $productModel->getByCategory(3, 6);
$articleD = $productModel->getByCategory(4, 6);

$sections = [
    'Article A' => $articleA,
    'Article B' => $articleB,
    'Article C' => $articleC,
    'Article D' => $articleD
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Online Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="logo">Logo</div>
    <div class="header-right">
        <a href="#" class="icon">👤</a>
        <a href="#" class="icon">🛒</a>
    </div>
</header>

<?php foreach ($sections as $title => $products): ?>
<section class="product-section">
    <div class="content-wrapper">
        <div class="section-inner">
            <div class="grid-wrapper">

                <div class="section-header">
                    <h2><?= $title ?></h2>
                    <a href="#" class="see-all">See all</a>
                </div>

                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <article class="product-card">
                            <div class="product-image">
                                <img
                                    src="assets/images/<?= htmlspecialchars($product['image']) ?>"
                                    alt="<?= htmlspecialchars($product['title']) ?>">
                            </div>

                            <h3><?= htmlspecialchars($product['title']) ?></h3>

                            <p class="price">
                                €<?= number_format($product['price'], 2, ',', '.') ?>
                            </p>

                            <?php if ($product['stock'] > 0): ?>
                                <p class="stock in-stock">✔ In stock</p>
                            <?php else: ?>
                                <p class="stock out-of-stock">✖ Out of stock</p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php endforeach; ?>

<footer class="footer">
    © Online Store Nick Vdv
</footer>

</body>
</html>