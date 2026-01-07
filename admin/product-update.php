<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

/* ALLEEN POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

/* DATA */
$id          = (int)($_POST['id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$price       = (float)($_POST['price'] ?? 0);
$stock       = (int)($_POST['stock'] ?? 0);
$image       = trim($_POST['image'] ?? '');
$description = trim($_POST['description'] ?? '');

/* BASIS VALIDATIE */
if ($id <= 0 || $title === '' || $price < 0 || $stock < 0) {
    die('Ongeldige invoer.');
}

/* DATABASE */
$db = new Database();
$conn = $db->connect();

/* UPDATE */
$stmt = $conn->prepare("
    UPDATE products
    SET title = ?, price = ?, stock = ?, image = ?, description = ?
    WHERE id = ?
");

$stmt->execute([
    $title,
    $price,
    $stock,
    $image,
    $description,
    $id
]);

/* TERUG */
header('Location: products.php');
exit;