<?php
require __DIR__ . '/includes/auth.php';

if (isset($_GET['deco'])) {
    session_destroy();
    header('LOCATION:index.php');
    exit();
}

$pageTitle = 'Stock - Administration - Tableau de bord';
require __DIR__ . '/partials/head.php';
?>
    <?php include('partials/nav.php'); ?>
    <div class="container-fluid">
        <h1>Tableau de bord</h1>
        <p>Bienvenue dans l'administration du portfolio.</p>
        <a href="dashboard.php?deco=1" class="btn btn-danger">Déconnexion</a>
    </div>
</body>
</html>
