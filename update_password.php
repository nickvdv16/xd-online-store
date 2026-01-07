<?php
session_start();
require_once 'includes/db.php'; // jouw PDO connectie

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: account.php');
    exit;
}

$password = $_POST['password'];
$confirm = $_POST['password_confirm'];

if ($password !== $confirm) {
    die('Wachtwoorden komen niet overeen.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    UPDATE users
    SET password = :password
    WHERE id = :id
");

$stmt->execute([
    ':password' => $hash,
    ':id' => $_SESSION['user']['id']
]);

header('Location: account.php');
exit;