<?php
session_start();

/* =========================
   AL INGELOGD? → REDIRECT
========================= */
if (isset($_SESSION['user_id'], $_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: /online_store/admin/index.php');
    } else {
        header('Location: /online_store/index.php');
    }
    exit;
}

/* =========================
   DATABASE CONNECTIE
========================= */
try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=online_store;charset=utf8mb4",
        "root",
        ""
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database fout");
}

$error = null;

/* =========================
   LOGIN VERWERKEN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Gelieve alle velden in te vullen.";
    } else {

        $stmt = $conn->prepare("
            SELECT id, name, password, role
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            /* SESSION BEVEILIGEN */
            session_regenerate_id(true);

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            /* ROLE REDIRECT */
            if ($user['role'] === 'admin') {
                header('Location: /online_store/admin/index.php');
            } else {
                header('Location: /online_store/index.php');
            }
            exit;

        } else {
            $error = "Ongeldig e-mailadres of wachtwoord.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- LOGIN / REGISTRATIE -->
    <link rel="stylesheet" href="assets/css/login-register.css">
</head>
<body>

<main class="login-page">

    <div class="login-card">

        <h1>Inloggen</h1>

        <?php if ($error): ?>
            <div class="form-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="login-form">

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                >
            </div>

            <button type="submit" class="btn primary">
                Inloggen
            </button>

        </form>

        <div class="form-footer">
            Nog geen account?
            <a href="signup.php">Nieuw account aanmaken</a>
        </div>

    </div>

</main>

</body>
</html>