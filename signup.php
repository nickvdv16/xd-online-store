<?php
session_start();

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
   REGISTRATIE VERWERKEN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Alle velden zijn verplicht.';
    } else {
        // Check of e-mail al bestaat
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $error = 'Dit e-mailadres bestaat al.';
        } else {
            // User aanmaken
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users (name, email, password, role)
                VALUES (:name, :email, :password, 'user')
            ");

            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $hashedPassword
            ]);

            // Na registratie → login
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>

    <!-- ALGEMEEN -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- LOGIN / REGISTRATIE -->
    <link rel="stylesheet" href="assets/css/login-register.css">
</head>
<body>

<main class="register-page">

    <div class="register-card">

        <h1>Registreren</h1>

        <?php if ($error): ?>
            <div class="form-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="register-form">

            <div class="form-group">
                <label for="name">Naam</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                >
            </div>

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
                Account aanmaken
            </button>

        </form>

        <div class="form-footer">
            Al een account?
            <a href="login.php">Inloggen</a>
        </div>

    </div>

</main>

</body>
</html>