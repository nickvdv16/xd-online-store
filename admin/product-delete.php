<?php
require_once __DIR__ . '/../includes/admin.php';
require_once __DIR__ . '/../classes/Database.php';

/* =========================
   ID CONTROLE
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$productId = (int) $_GET['id'];

/* =========================
   DATABASE
========================= */
$db = new Database();
$conn = $db->connect();

/* =========================
   CHECK OF PRODUCT BESTAAT
========================= */
$check = $conn->prepare("
    SELECT id
    FROM products
    WHERE id = ?
");
$check->execute([$productId]);

if ($check->rowCount() === 0) {
    // Product bestaat niet
    header('Location: products.php');
    exit;
}

/* =========================
   PRODUCT VERWIJDEREN
========================= */
$delete = $conn->prepare("
    DELETE FROM products
    WHERE id = ?
");
$delete->execute([$productId]);

/* =========================
   REDIRECT
========================= */
header('Location: products.php');
exit;