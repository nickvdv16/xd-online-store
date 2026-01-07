<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* CART COUNT */
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

/* USER */
$user = null;

if (isset($_SESSION['user_id'])) {
    require_once 'classes/Database.php';

    $db = new Database();
    $conn = $db->connect(); // ✅ JUISTE METHODE

    $stmt = $conn->prepare("
        SELECT name, store_credit 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<header class="header">
    <div class="header-inner">

        <a href="index.php" class="logo">
            <img src="assets/images/Logo.png" alt="Logo">
        </a>

        <div class="header-icons">

            <?php if ($user): ?>
                <a href="account.php" class="icon user logged-in">
                    <img src="assets/images/Account.png" alt="Account">
                    <div class="user-meta">
                        <span class="user-name">
                            <?= htmlspecialchars($user['name']) ?>
                        </span>
                        <span class="user-credit">
                            €<?= number_format($user['store_credit'], 2, ',', '.') ?>
                        </span>
                    </div>
                </a>
            <?php else: ?>
                <a href="login.php" class="icon user">
                    <img src="assets/images/Account.png" alt="Login">
                </a>
            <?php endif; ?>

            <a href="cart.php" class="icon cart">
                <img src="assets/images/Checkout.png" alt="Winkelwagen">
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>

        </div>
    </div>
</header>