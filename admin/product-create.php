<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

/* CATEGORIEËN OPHALEN */
$db = new Database();
$conn = $db->connect();

$catStmt = $conn->query("SELECT id, name FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin – Nieuw product</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">
</head>
<body>

<main class="admin-products">

    <h1>Nieuw product toevoegen</h1>

    <form action="product-store.php" method="post" class="product-form">

        <!-- RIJ 1 -->
        <div class="form-row">
            <div class="form-group">
                <label>Titel</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Prijs (€)</label>
                <input type="number" step="0.01" name="price" required>
            </div>

            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" required>
            </div>
        </div>

        <!-- RIJ 2 -->
        <div class="form-row">
            <div class="form-group">
                <label>Categorie</label>
                <select name="category_id" required>
                    <option value="">– Kies categorie –</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Afbeelding (bestandsnaam)</label>
                <input type="text" name="image" placeholder="voorbeeld.png">
            </div>
        </div>

        <!-- BESCHRIJVING -->
        <div class="form-group">
            <label>Beschrijving</label>
            <textarea name="description" rows="5"></textarea>
        </div>

        <!-- ACTIES -->
        <div class="form-actions">
            <button type="submit" class="btn primary">Opslaan</button>
            <a href="products.php" class="btn secondary">Annuleren</a>
        </div>

    </form>

</main>

</body>
</html>