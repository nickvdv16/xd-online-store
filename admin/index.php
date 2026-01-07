<?php
require_once '../includes/admin.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin dashboard</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header class="header">
    <div class="header-inner">
        <h2 style="color:white;">Admin panel</h2>

        <a href="../logout.php" class="btn secondary">
            Uitloggen
        </a>
    </div>
</header>

<main class="admin-page">

    <h1>Admin dashboard</h1>

    <div class="admin-grid">

        <a href="products.php" class="admin-card">
            <h3>Producten</h3>
            <p>Beheer producten & stock</p>
        </a>

        <a href="orders.php" class="admin-card">
            <h3>Bestellingen</h3>
            <p>Bekijk alle orders</p>
        </a>

        <a href="users.php" class="admin-card">
            <h3>Gebruikers</h3>
            <p>Beheer gebruikers</p>
        </a>

    </div>

</main>

</body>
</html>