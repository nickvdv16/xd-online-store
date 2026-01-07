<?php
require_once 'classes/Product.php';

$productModel = new Product();

$sections = [
    1 => [
        'title' => 'Losse kaarten',
        'products' => $productModel->getByCategory(1, 6)
    ],
    2 => [
        'title' => 'Booster Packs',
        'products' => $productModel->getByCategory(2, 6)
    ],
    3 => [
        'title' => 'Booster Box',
        'products' => $productModel->getByCategory(3, 6)
    ],
    4 => [
        'title' => 'Elite Trainer Box',
        'products' => $productModel->getByCategory(4, 6)
    ]
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Online Store</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
<?php foreach ($sections as $categoryId => $section): ?>
<section class="product-section">
    <div class="content-wrapper">
        <div class="section-inner">
            <div class="grid-wrapper">

                <div class="section-header">
                    <h2><?= htmlspecialchars($section['title']) ?></h2>
                    <a href="category.php?id=<?= $categoryId ?>" class="see-all">
                        See all
                    </a>
                </div>

                <div class="product-grid">
                    <?php foreach ($section['products'] as $product): ?>
                        <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                            <article class="product-card">

                                <div class="product-image">
                                    <img src="assets/images/<?= htmlspecialchars($product['image']) ?>">
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
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>