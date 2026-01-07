<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Niet ingelogd → naar login */
if (!isset($_SESSION['user_id'])) {
    header('Location: /online_store/login.php');
    exit;
}

/* Geen admin → terug naar shop */
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /online_store/index.php');
    exit;
}