<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

/* DATABASE */
$db = new Database();
$conn = $db->connect();

/* PRODUCTEN + CATEGORIE OPHALEN */
$stmt = $conn->query("
    SELECT 
        p.id,
        p.title,
        p.price,
        p.stock,
        p.image,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin – Producten</title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- ADMIN -->
    <link rel="stylesheet" href="../assets/css/admin-products.css">
</head>
<body>

<main class="admin-products">

    <!-- HEADER -->
    <div class="admin-header">
        <h1>Producten beheren</h1>

        <div class="admin-header-actions">
            <a href="index.php" class="btn secondary">
                ← Dashboard
            </a>

            <a href="product-create.php" class="btn primary">
                + Product toevoegen
            </a>
        </div>
    </div>

    <!-- TABEL -->
    <table class="product-table">
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Titel</th>
                <th>Categorie</th>
                <th>Prijs</th>
                <th>Stock</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($products as $product): ?>
            <?php
                $image = !empty($product['image'])
                    ? $product['image']
                    : 'placeholder.png';
            ?>
            <tr>
                <td>
                    <img
                        src="../assets/images/<?= htmlspecialchars($image) ?>"
                        alt="<?= htmlspecialchars($product['title']) ?>"
                    >
                </td>

                <td><?= htmlspecialchars($product['title']) ?></td>

                <td>
                    <?= htmlspecialchars($product['category_name'] ?? '—') ?>
                </td>

                <td>
                    €<?= number_format((float)$product['price'], 2, ',', '.') ?>
                </td>

                <td><?= (int)$product['stock'] ?></td>

                <td class="actions">
                    <a
                        href="product-edit.php?id=<?= (int)$product['id'] ?>"
                        class="btn primary"
                    >
                        Bewerken
                    </a>

                    <a
                        href="product-delete.php?id=<?= (int)$product['id'] ?>"
                        class="btn secondary"
                        onclick="return confirm('Product verwijderen?')"
                    >
                        Verwijderen
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</main>

</body>
</html>