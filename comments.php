<?php
session_start();
require_once 'classes/Database.php';

$db = new Database();
$conn = $db->connect();

/* =========================
   POST → REACTIE OPSLAAN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Niet ingelogd'
        ]);
        exit;
    }

    $comment = trim($_POST['comment'] ?? '');
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($comment === '' || $productId === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Ongeldige invoer'
        ]);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO comments (user_id, product_id, comment, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $productId,
        $comment
    ]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================
   GET → REACTIES OPHALEN
========================= */
$productId = (int)($_GET['product_id'] ?? 0);

$stmt = $conn->prepare("
    SELECT c.comment, c.created_at, u.name
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE c.product_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$productId]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));