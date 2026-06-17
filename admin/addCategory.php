<?php
require __DIR__ . '/includes/auth.php';
$pageTitle = 'Stock - Administration - Ajouter une catégorie';
require __DIR__ . '/partials/head.php';
?>
    <?php include('partials/nav.php'); ?>
    <div class="container-fluid">
        <h1>Ajouter une catégorie</h1>
        <a href="categories.php" class="btn btn-secondary my-2">Retour</a>
        <div class="container">
            <form action="treatmentAddCategory.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Une erreur est survenue (code erreur: <?= htmlspecialchars($_GET['error']) ?>)</div>
                <?php endif; ?>
                <div class="form-group my-3">
                    <label for="nom">Nom de la catégorie</label>
                    <input type="text" id="nom" name="nom" class="form-control">
                </div>
                <div class="form-group my-3">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" value="Ajouter" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
