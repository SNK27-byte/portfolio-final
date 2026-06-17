<?php
session_start();
if(!isset($_SESSION['login']))
{
    header("LOCATION:index.php");
    exit();
}



if(!isset($_GET['id']) && !is_numeric($_GET['id']))
{
    header("LOCATION:products.php");
    exit();
}else // sinon on continue
{
    // proctection de l'id pour son utilisation dans la bdd
    $id = htmlspecialchars($_GET['id']);
}


require "../config/connexion.php";

$req = $bdd->prepare("SELECT * FROM products WHERE id=?");
$req->execute([$id]);
$don = $req->fetch(PDO::FETCH_ASSOC);
if(!$don)
{
    header("LOCATION:products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Stock - Administration - Ajouter une image à <?= $don['name'] ?></title>
</head>
<body>
<?php
include("partials/nav.php");
?>
<div class="container-fluid">
    <h1> Ajouter une image à <?= $don['name'] ?></h1>
    <a href="updateProduct.php?id=<?= $id ?>" class="btn btn-secondary my-2">Retour</a>
    <div class="container">
                <form action="treatmentAddImg.php?id=<?= $don['id'] ?>" method="POST" enctype="multipart/form-data">
                    <?php
                    if(isset($_GET['errorImg']))
                    {
                        require __DIR__ . '/includes/upload-limits.php';
                        $errorImg = (int) $_GET['errorImg'];
                        $message = uploadErrorMessage($errorImg);
                        if ($errorImg === 7 && isset($_GET['size']) && is_numeric($_GET['size'])) {
                            $message .= ' Taille envoyée : ' . formatBytes((int) $_GET['size']) . '.';
                        }
                        echo "<div class='alert alert-danger'>{$message}</div>";
                    }
                    ?>               
                    <div class="form-group my-3">
                        <label for="fichier">Image</label>
                        <input type="file" id="fichier" name="fichier" class="form-control" accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Ajouter" class="btn btn-success">
                    </div>
                </form>
    </div>
</div>
</body>
</html>

