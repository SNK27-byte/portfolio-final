<?php
require __DIR__ . '/includes/auth.php';
$pageTitle = 'Stock - Administration - Ajouter un produit';
require __DIR__ . '/partials/head.php';
?>
    <?php include('partials/nav.php'); ?>
    <div class="container-fluid">
        <h1>Ajouter un produit</h1>
        <a href="products.php" class="btn btn-secondary my-2">Retour</a>
        <div class="container">
            <form action="treatmentAddProduct.php" method="POST" enctype="multipart/form-data">
                <?php
                    // isset => si existe
                    // si tu vois dans l'URL ?error=123 alors c'est que tu as une erreur
                    if(isset($_GET['error']))
                    {
                        $fieldErrors = [
                            1 => 'Le nom du produit est obligatoire.',
                            2 => 'La date est obligatoire.',
                            3 => 'La description est obligatoire.',
                            4 => 'La catégorie est obligatoire.',
                        ];
                        $errorCode = (int) $_GET['error'];
                        $message = $fieldErrors[$errorCode] ?? "Une erreur est survenue (code erreur: {$errorCode}).";
                        echo "<div class='alert alert-danger'>{$message}</div>";
                    }

                    if(isset($_GET['errorImg']))
                    {
                        $errorImg = (int) $_GET['errorImg'];
                        $message = uploadErrorMessage($errorImg);
                        if ($errorImg === 7 && isset($_GET['size']) && is_numeric($_GET['size'])) {
                            $message .= ' Taille envoyée : ' . formatBytes((int) $_GET['size']) . '.';
                        }
                        echo "<div class='alert alert-danger'>{$message}</div>";
                    }
                ?>
                <div class="form-group my-3">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom" class="form-control">
                </div>
                <div class="form-group my-3">
                    <label for="date">date</label>
                    <input type="date" id="date" name="date" class="form-control">
                </div>
                <div class="form-group my-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
                <div class="form-group my-3">
                    <label for="cover">Image de couverture</label>
                    <input type="file" id="cover" name="cover" class="form-control" accept="image/jpeg,image/png,.jpg,.jpeg,.png">
                    <small class="text-muted">JPG ou PNG, <?= uploadMaxSizeLabel() ?> maximum.</small>
                </div>
                <div class="form-group my-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-control">
                       <?php
                            require "../config/connexion.php";
                            $req = $bdd->query("SELECT * FROM categories");
                            while($don = $req->fetch())
                            {
                                echo "<option value='".$don['id']."'>".$don['name']."</option>";
                            }
                            $req->closeCursor();
                       ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Ajouter" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</body>
</html>