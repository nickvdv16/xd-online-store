<?php
require_once 'classes/Product.php';

if (!isset($_GET['id'])) {
    die('Geen categorie geselecteerd.');
}

$categoryId = (int) $_GET['id'];
$sort = $_GET['sort'] ?? '';

$productModel = new Product();

/* =========================
   SORTERING
========================= */
switch ($sort) {
    case 'price_asc':
        $products = $productModel->getByCategorySorted($categoryId, 'price', 'ASC');
        break;

    case 'price_desc':
        $products = $productModel->getByCategorySorted($categoryId, 'price', 'DESC');
        break;

    case 'name_asc':
        $products = $productModel->getByCategorySorted($categoryId, 'title', 'ASC');
        break;

    default:
        $products = $productModel->getByCategory($categoryId);
        break;
}

/* =========================
   CATEGORIE NAMEN
========================= */
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

    <!-- HEADER / FOOTER -->
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- CATEGORY -->
    <link rel="stylesheet" href="assets/css/category.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="category-page">

    <!-- =========================
         TOP BAR
    ========================= -->
    <div class="category-top">

        <!-- CATEGORIE KNOPPEN -->
        <div class="category-filters">
            <a href="category.php?id=1" class="<?= $categoryId === 1 ? 'active' : '' ?>">Losse kaarten</a>
            <a href="category.php?id=2" class="<?= $categoryId === 2 ? 'active' : '' ?>">Booster Packs</a>
            <a href="category.php?id=3" class="<?= $categoryId === 3 ? 'active' : '' ?>">Booster Box</a>
            <a href="category.php?id=4" class="<?= $categoryId === 4 ? 'active' : '' ?>">Elite Trainer Box</a>
        </div>

        <!-- SORTERING -->
        <div class="category-sort">
            <form method="get">
                <input type="hidden" name="id" value="<?= $categoryId ?>">

                <select name="sort" onchange="this.form.submit()">
                    <option value="">Standaard sortering</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>
                        Prijs: laag → hoog
                    </option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>
                        Prijs: hoog → laag
                    </option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>
                        Naam: A → Z
                    </option>
                </select>
            </form>
        </div>

    </div>

    <!-- =========================
         TITEL
    ========================= -->
    <h1 class="category-title"><?= htmlspecialchars($categoryTitle) ?></h1>

    <!-- =========================
         PRODUCT GRID
    ========================= -->
    <div class="content-wrapper">
        <div class="section-inner">
            <div class="grid-wrapper">

                <div class="product-grid">
                    <?php foreach ($products as $product): ?>

                        <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                            <article class="product-card">

                                <div class="product-image">
                                    <img
                                        src="assets/images/<?= htmlspecialchars($product['image']) ?>"
                                        alt="<?= htmlspecialchars($product['title']) ?>"
                                    >
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

<?php include 'includes/footer.php'; ?>

</body>
</html>