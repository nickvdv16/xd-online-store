<?php
require_once 'includes/auth.php';
require_once 'classes/Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$conn = $db->connect();

/* USER */
$stmt = $conn->prepare("
    SELECT id, name, email, store_credit
    FROM users
    WHERE id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* WACHTWOORD WIJZIGEN */
$passwordError = null;
$passwordSuccess = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($newPassword === '' || $confirmPassword === '') {
        $passwordError = "Vul beide wachtwoordvelden in.";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordError = "Wachtwoorden komen niet overeen.";
    } else {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $conn->prepare("
            UPDATE users 
            SET password = ? 
            WHERE id = ?
        ");
        $update->execute([$hash, $user['id']]);

        $passwordSuccess = "Wachtwoord succesvol gewijzigd.";
    }
}

/* BESTELLINGEN */
$orderStmt = $conn->prepare("
    SELECT o.id, o.created_at, o.total
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$orderStmt->execute([$user['id']]);
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn account</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/account.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="account-page">

    <h1>Mijn account</h1>

    <div class="account-layout">

        <!-- LINKERKANT -->
        <div class="account-left">

            <h2>Mijn gegevens</h2>

            <div class="account-box">
                <p><strong>Naam:</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Store krediet:</strong>
                    €<?= number_format($user['store_credit'], 2, ',', '.') ?>
                </p>
            </div>

            <h3>Wachtwoord veranderen</h3>

            <div class="account-box">
                <?php if ($passwordError): ?>
                    <p style="color:red; font-size:14px;"><?= htmlspecialchars($passwordError) ?></p>
                <?php endif; ?>

                <?php if ($passwordSuccess): ?>
                    <p style="color:green; font-size:14px;"><?= htmlspecialchars($passwordSuccess) ?></p>
                <?php endif; ?>

                <form method="post">
                    <input
                        type="password"
                        name="new_password"
                        placeholder="Nieuw wachtwoord"
                        required
                    >
                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Bevestig wachtwoord"
                        required
                    >

                    <button type="submit" class="btn-primary">
                        Bevestigen
                    </button>
                </form>
            </div>

            <a href="logout.php" class="btn-primary logout-btn">
                Uitloggen
            </a>

        </div>

        <!-- RECHTERKANT -->
        <div class="account-right">

            <h2>Mijn bestellingen</h2>

            <?php if (empty($orders)): ?>
                <p>Je hebt nog geen bestellingen geplaatst.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>

                    <div class="order-box">
                        <div class="order-header">
                            <strong>Bestelling #<?= $order['id'] ?></strong>
                            <span>
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </span>
                        </div>

                        <?php
                        $itemsStmt = $conn->prepare("
                            SELECT oi.quantity, oi.price, p.title, p.image
                            FROM order_items oi
                            JOIN products p ON p.id = oi.product_id
                            WHERE oi.order_id = ?
                        ");
                        $itemsStmt->execute([$order['id']]);
                        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <?php foreach ($items as $item): ?>
                            <div class="order-item">
                                <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="">
                                <div class="order-item-info">
                                    <span><?= htmlspecialchars($item['title']) ?></span>
                                    <small>Aantal: <?= $item['quantity'] ?></small>
                                </div>
                                <div class="order-item-price">
                                    €<?= number_format($item['price'], 2, ',', '.') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="order-total">
                            <strong>Totaal</strong>
                            <strong>
                                €<?= number_format($order['total'], 2, ',', '.') ?>
                            </strong>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>

    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>