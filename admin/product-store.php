<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

/* INPUT */
$title       = trim($_POST['title'] ?? '');
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? '';
$category_id = $_POST['category_id'] ?? '';
$image       = trim($_POST['image'] ?? '');
$description = trim($_POST['description'] ?? '');

/* VALIDATIE */
if (
    $title === '' ||
    !is_numeric($price) || $price <= 0 ||
    !is_numeric($stock) || $stock < 0 ||
    !is_numeric($category_id)
) {
    header('Location: product-create.php');
    exit;
}

/* DATABASE */
$db = new Database();
$conn = $db->connect();

/* INSERT */
$stmt = $conn->prepare("
    INSERT INTO products 
    (category_id, title, description, price, image, stock)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    (int)$category_id,
    $title,
    $description !== '' ? $description : null,
    (float)$price,
    $image !== '' ? $image : null,
    (int)$stock
]);

header('Location: products.php');
exit;