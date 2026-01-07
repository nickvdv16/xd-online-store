<?php
require_once 'includes/auth.php';
require_once 'classes/Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================
   CART
========================= */
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

/* =========================
   USER
========================= */
$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("
    SELECT id, name, email, store_credit 
    FROM users 
    WHERE id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   TOTAAL
========================= */
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

/* =========================
   FEEDBACK
========================= */
$error = null;

/* =========================
   FORM SUBMIT
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postcode = trim($_POST['postcode'] ?? '');
    $plaats   = trim($_POST['plaats'] ?? '');
    $payment  = $_POST['payment'] ?? '';

    if ($postcode === '' || $plaats === '') {
        $error = "Vul alle verplichte velden in.";
    }
    elseif (!in_array($payment, ['store_credit', 'bancontact'])) {
        $error = "Selecteer een betaalmethode.";
    }
    elseif ($payment === 'store_credit' && $user['store_credit'] < $total) {
        $error = "Onvoldoende store krediet.";
    }
    else {
        try {
            $conn->beginTransaction();

            /* 1️⃣ ORDER OPSLAAN */
            $orderStmt = $conn->prepare("
                INSERT INTO orders (user_id, total)
                VALUES (?, ?)
            ");
            $orderStmt->execute([
                $user['id'],
                $total
            ]);

            $orderId = $conn->lastInsertId();

            /* 2️⃣ ORDER ITEMS OPSLAAN */
            $itemStmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($cart as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            /* 3️⃣ STORE KREDIET AANPASSEN (ENKEL BIJ STORE_CREDIT) */
            if ($payment === 'store_credit') {
                $newCredit = $user['store_credit'] - $total;

                $creditStmt = $conn->prepare("
                    UPDATE users
                    SET store_credit = ?
                    WHERE id = ?
                ");
                $creditStmt->execute([$newCredit, $user['id']]);
            }

            /* 4️⃣ CART LEEGMAKEN */
            unset($_SESSION['cart']);

            /* 5️⃣ TRANSACTIE BEVESTIGEN */
            $conn->commit();

            header("Location: account.php?order=success");
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Er is iets misgelopen bij het plaatsen van de bestelling.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Afrekenen</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="checkout-page">

    <h1>Afrekenen</h1>

    <?php if ($error): ?>
        <div class="checkout-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="checkout-layout">

        <!-- INFORMATIE -->
        <div class="checkout-box">
            <h2>Informatie</h2>

            <label>Naam</label>
            <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly>

            <label>E-mail</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

            <label>Postcode</label>
            <input type="text" name="postcode" required>

            <label>Plaats</label>
            <input type="text" name="plaats" required>
        </div>

        <!-- OVERZICHT -->
        <div class="checkout-box">
            <h2>Overzicht</h2>

            <?php foreach ($cart as $item): ?>
                <div class="summary-row">
                    <span>
                        <?= htmlspecialchars($item['title']) ?> × <?= $item['quantity'] ?>
                    </span>
                    <span>
                        €<?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <div class="summary-total">
                <strong>Totaal</strong>
                <strong>€<?= number_format($total, 2, ',', '.') ?></strong>
            </div>

            <button type="submit" class="btn primary">
                Bestelling plaatsen
            </button>

            <a href="cart.php" class="btn secondary">
                Terug naar winkelwagen
            </a>
        </div>

        <!-- BETALING -->
        <div class="checkout-box">
            <h2>Betaling</h2>

            <label class="radio">
                <input type="radio" name="payment" value="store_credit" checked>
                Store krediet (beschikbaar:
                €<?= number_format($user['store_credit'], 2, ',', '.') ?>)
            </label>

            <label class="radio">
                <input type="radio" name="payment" value="bancontact">
                Bancontact
            </label>
        </div>

    </form>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>