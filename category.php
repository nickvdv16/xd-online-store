<?php
require_once 'classes/Product.php';

if (!isset($_GET['id'])) {
    die('Geen categorie geselecteerd.');
}

$categoryId = (int) $_GET['id'];

$productModel = new Product();
$products = $productModel->getByCategory($categoryId);

/* Categorie namen (tijdelijk hardcoded) */
$categoryNames = [
    1 => 'Losse kaarten',
    2 => 'Booster Packs',
    3 => 'Booster Box',
    4 => 'Elite Trainer Box'
];

$categoryTitle = $categoryNames[$categoryId] ?? 'Categorie';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoryTitle) ?></title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- HEADER -->
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- CATEGORY -->
    <link rel="stylesheet" href="assets/css/category.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="category-page">

    <!-- TOP BAR -->
    <div class="category-top">

        <!-- CATEGORIE KNOPPEN (UI ONLY) -->
        <div class="category-filters">
            <a href="category.php?id=1" class="<?= $categoryId === 1 ? 'active' : '' ?>">Losse kaarten</a>
            <a href="category.php?id=2" class="<?= $categoryId === 2 ? 'active' : '' ?>">Booster Packs</a>
            <a href="category.php?id=3" class="<?= $categoryId === 3 ? 'active' : '' ?>">Booster Box</a>
            <a href="category.php?id=4" class="<?= $categoryId === 4 ? 'active' : '' ?>">Elite Trainer Box</a>
        </div>

        <!-- SORTERING (UI ONLY) -->
        <div class="category-sort">
            <select>
                <option>Standaard sortering</option>
                <option>Prijs: laag → hoog</option>
                <option>Prijs: hoog → laag</option>
            </select>
        </div>

    </div>

    <!-- TITEL -->
    <h1 class="category-title"><?= htmlspecialchars($categoryTitle) ?></h1>

    <!-- PRODUCT GRID -->
    <div class="content-wrapper">
        <div class="section-inner">
            <div class="grid-wrapper">

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

</main>

<footer class="footer">
    © Online Store Werkt
</footer>

</body>
</html>