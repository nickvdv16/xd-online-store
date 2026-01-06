<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">

    <!-- LOGO LINKS -->
    <a href="index.php" class="logo">
        <img src="assets/images/Logo.png" alt="Logo">
    </a>

    <!-- ICONS RECHTS -->
    <div class="header-icons">

        <a href="#" class="header-icon">
            <img src="assets/images/Account.png" alt="User">
        </a>

        <a href="cart.php" class="header-icon">
            <img src="assets/images/Checkout.png" alt="Winkelwagen">
        </a>

    </div>

</header>