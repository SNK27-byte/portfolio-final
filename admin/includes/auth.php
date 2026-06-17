<?php
// Vérifie que l'admin est connecté avant d'ouvrir une page protégée.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header('LOCATION:index.php');
    exit();
}
