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

$stock = (int)$product['stock'];

/* =========================
   TOEVOEGEN AAN WINKELWAGEN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    if ($stock <= 0) {
        header('Location: product.php?id=' . $product['id']);
        exit;
    }

    $id = (int)$product['id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($qty < 1) $qty = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $currentQty = $_SESSION['cart'][$id]['quantity'] ?? 0;
    $newQty = min($currentQty + $qty, $stock);

    $_SESSION['cart'][$id] = [
        'id' => $id,
        'title' => $product['title'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => $newQty,
        'stock' => $stock
    ];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?></title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/product.css">
    <link rel="stylesheet" href="assets/css/comments.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="product-page">

    <!-- PRODUCT INFO -->
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

            <?php if ($stock > 0): ?>
                <p class="stock in-stock">✔ In stock (<?= $stock ?>)</p>
            <?php else: ?>
                <p class="stock out-of-stock">✖ Out of stock</p>
            <?php endif; ?>

            <?php if ($stock > 0): ?>
                <form method="post" class="add-to-cart-form">

                    <div class="quantity">
                        <button type="button" id="decrease">−</button>
                        <span id="qty">1</span>
                        <button type="button" id="increase">+</button>
                    </div>

                    <input 
                        type="hidden" 
                        name="quantity" 
                        id="quantityInput" 
                        value="1"
                        data-max="<?= $stock ?>"
                    >

                    <button type="submit" name="add_to_cart" class="add-to-cart">
                        In winkelwagen
                    </button>

                </form>
            <?php else: ?>
                <button class="add-to-cart disabled" disabled>
                    Niet beschikbaar
                </button>
            <?php endif; ?>
        </div>

    </section>

    <!-- OMSCHRIJVING -->
    <section class="product-description">
        <h2>Productomschrijving</h2>
        <div class="description-box">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Geen beschrijving beschikbaar.')) ?>
        </div>
    </section>

    <!-- =========================
         COMMENTS (AJAX)
    ========================= -->
    <section class="product-comments">

        <h2>Reacties</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form id="commentForm">
                <textarea 
                    name="comment" 
                    placeholder="Schrijf een reactie..." 
                    required
                ></textarea>

                <input 
                    type="hidden" 
                    name="product_id" 
                    value="<?= $product['id'] ?>"
                >

                <button type="submit" class="btn primary">
                    Plaatsen
                </button>
            </form>
        <?php else: ?>
            <p>
                <a href="login.php">Log in</a> om een reactie te plaatsen.
            </p>
        <?php endif; ?>

        <div id="commentsList"></div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script src="assets/js/product.js"></script>
<script src="assets/js/comments.js"></script>

</body>
</html>