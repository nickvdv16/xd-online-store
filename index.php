<?php
require_once 'classes/Product.php';

$productModel = new Product();

$sections = [
    'Article A' => $productModel->getByCategory(1, 6),
    'Article B' => $productModel->getByCategory(2, 6),
    'Article C' => $productModel->getByCategory(3, 6),
    'Article D' => $productModel->getByCategory(4, 6)
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

<header class="header">
    <div class="logo">Logo</div>
    <div class="header-right">
        <span class="icon">👤</span>
        <span class="icon">🛒</span>
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

                        <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                            <article class="product-card">

                                <div class="product-image">
                                    <img src="assets/images/<?= htmlspecialchars($product['image']) ?>"
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
                        </a>

                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php endforeach; ?>

<footer class="footer">
    © Online Store Werkt
</footer>

</body>
</html>