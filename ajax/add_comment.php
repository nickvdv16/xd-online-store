<?php
require_once '../includes/auth.php';
require_once '../classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
$comment   = trim($_POST['comment'] ?? '');
$userId    = $_SESSION['user_id'];

if ($productId <= 0 || $comment === '') {
    http_response_code(400);
    exit;
}

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("
    INSERT INTO comments (product_id, user_id, comment)
    VALUES (?, ?, ?)
");
$stmt->execute([$productId, $userId, $comment]);

echo json_encode(['success' => true]);