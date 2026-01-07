<?php
require_once '../classes/Database.php';

$productId = (int) ($_GET['product_id'] ?? 0);

if ($productId <= 0) {
    exit;
}

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("
    SELECT c.comment, c.created_at, u.name
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE c.product_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$productId]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comments);