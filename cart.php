<?php
session_start();

$cart = $_SESSION['cart'] ?? [];

/* Acties */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['increase'])) {
        $id = (int)$_POST['increase'];
        $_SESSION['cart'][$id]['quantity']++;
    }

    if (isset($_POST['decrease'])) {
        $id = (int)$_POST['decrease'];
        $_SESSION['cart'][$id]['quantity']--;
        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    if (isset($_POST['remove'])) {
        $id = (int)$_POST['remove'];
        unset($_SESSION['cart'][$id]);
    }

    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- HEADER -->
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- CART -->
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="cart-page">

    <h1>Winkelwagen</h1>

    <?php if (empty($cart)): ?>

        <div class="cart-empty">
            <p>Je winkelwagen is leeg.</p>
            <a href="index.php" class="btn">Verder winkelen</a>
        </div>

    <?php else: ?>

    <div class="cart-layout">

        <!-- LINKERKANT: PRODUCTEN -->
        <section class="cart-items">

            <div class="cart-header">
                <span>Product</span>
                <span>Prijs</span>
                <span>Aantal</span>
                <span>Subtotaal</span>
                <span></span>
            </div>

            <?php
            $total = 0;
            foreach ($cart as $item):
                $sub = $item['price'] * $item['quantity'];
                $total += $sub;
            ?>
            <div class="cart-row">

                <div class="cart-product">
                    <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="">
                    <span><?= htmlspecialchars($item['title']) ?></span>
                </div>

                <div>€<?= number_format($item['price'], 2, ',', '.') ?></div>

                <div class="cart-quantity">
                    <form method="post">
                        <button name="decrease" value="<?= $item['id'] ?>">−</button>
                    </form>

                    <span><?= $item['quantity'] ?></span>

                    <form method="post">
                        <button name="increase" value="<?= $item['id'] ?>">+</button>
                    </form>
                </div>

                <div>
                    €<?= number_format($sub, 2, ',', '.') ?>
                </div>

                <div>
                    <form method="post">
                        <button class="remove" name="remove" value="<?= $item['id'] ?>">✕</button>
                    </form>
                </div>

            </div>
            <?php endforeach; ?>

        </section>

        <!-- RECHTERKANT: TOTAAL -->
        <aside class="cart-summary">
            <h2>Overzicht</h2>

            <div class="summary-row">
                <span>Totaal</span>
                <strong>€<?= number_format($total, 2, ',', '.') ?></strong>
            </div>

            <a href="checkout.php" class="btn primary">Doorgaan naar afrekenen</a>
            <a href="index.php" class="btn secondary">Verder winkelen</a>
        </aside>

    </div>

    <?php endif; ?>

</main>

<footer class="footer">
    © Online Store
</footer>

</body>
</html>