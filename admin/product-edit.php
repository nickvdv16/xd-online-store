<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

/* =========================
   ID CONTROLE
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Ongeldig product-ID');
}

$productId = (int)$_GET['id'];

/* =========================
   DATABASE
========================= */
$db = new Database();
$conn = $db->connect();

/* =========================
   PRODUCT OPHALEN
========================= */
$stmt = $conn->prepare("
    SELECT id, title, price, stock, image, description
    FROM products
    WHERE id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Product niet gevonden');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin – Product bewerken</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">
</head>
<body>

<main class="admin-products">

    <h1>Product bewerken</h1>

    <form method="post" action="product-update.php" class="admin-form">

    <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">

    <div class="form-group">
        <label>Titel</label>
        <input type="text" name="title"
               value="<?= htmlspecialchars($product['title']) ?>" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Prijs (€)</label>
            <input type="number" step="0.01" name="price"
                   value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock"
                   value="<?= (int)$product['stock'] ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Afbeelding (bestandsnaam)</label>
        <input type="text" name="image"
               value="<?= htmlspecialchars($product['image']) ?>">
    </div>

    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn primary">Opslaan</button>
        <a href="products.php" class="btn secondary">Annuleren</a>
    </div>

</form>

</main>

</body>
</html>