<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/upload-limits.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('LOCATION:products.php');
    exit();
}

$id = (int) $_GET['id'];
require __DIR__ . '/../config/connexion.php';

$req = $bdd->prepare('SELECT * FROM products WHERE id = ?');
$req->execute([$id]);
$don = $req->fetch(PDO::FETCH_ASSOC);

if (!$don) {
    header('LOCATION:products.php');
    exit();
}

$pageTitle = 'Stock - Administration - Modifier une oeuvre';
require __DIR__ . '/partials/head.php';
?>
    <?php include('partials/nav.php'); ?>
    <div class="container-fluid">
        <h1>Modifier une oeuvre</h1>
        <a href="products.php" class="btn btn-secondary my-2">Retour</a>
        <div class="container">
            <form action="treatmentUpdateProduct.php?id=<?= (int) $don['id'] ?>" method="POST" enctype="multipart/form-data">
                <?php
                    if (isset($_GET['error'])) {
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

                    if (isset($_GET['errorImg'])) {
                        $errorImg = (int) $_GET['errorImg'];
                        $message = uploadErrorMessage($errorImg);
                        echo "<div class='alert alert-danger'>{$message}</div>";
                    }
                ?>
                <div class="form-group my-3">
                    <label for="nom">Nom de l'oeuvre</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($don['name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group my-3">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($don['date'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="form-group my-3">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="8"><?= htmlspecialchars(html_entity_decode((string) $don['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?></textarea>
                    <small class="text-muted">Ce texte s'affiche dans la fiche détail quand on clique sur l'oeuvre dans le carrousel.</small>
                </div>
                <div class="form-group my-3">
                    <label>Image de couverture actuelle</label>
                    <div class="col-4 mb-2">
                        <img src="../images/<?= htmlspecialchars($don['cover'], ENT_QUOTES, 'UTF-8') ?>" alt="image de l'oeuvre - <?= htmlspecialchars($don['name'], ENT_QUOTES, 'UTF-8') ?>" class="img-fluid">
                    </div>
                    <label for="cover">Remplacer l'image (optionnel)</label>
                    <input type="file" id="cover" name="cover" class="form-control" accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif">
                    <small class="text-muted">JPG, PNG ou GIF, <?= uploadMaxSizeLabel() ?> maximum.</small>
                </div>
                <div class="form-group my-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-control">
                        <?php
                            $catReq = $bdd->query('SELECT id, name FROM categories ORDER BY name ASC');
                            while ($cat = $catReq->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ((int) $cat['id'] === (int) $don['category']) ? ' selected' : '';
                                echo '<option value="' . (int) $cat['id'] . '"' . $selected . '>' . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            $catReq->closeCursor();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Modifier" class="btn btn-warning">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
