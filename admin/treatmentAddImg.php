<?php
<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/upload-limits.php';

    // vérification de la présence de l'id
    if(!isset($_GET['id']) && !is_numeric($_GET['id']))
    {
        header("LOCATION:products.php");
        exit();
    }else // sinon on continue
    {
        // proctection de l'id pour son utilisation dans la bdd
        $id = htmlspecialchars($_GET['id']);
    }

    // vérifier que le produit existe vraiment pour lui ajouter l'image
    require "../config/connexion.php";

    $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
    $req->execute([$id]);
    $don = $req->fetch(PDO::FETCH_ASSOC);
    if(!$don)
    {
        header("LOCATION:products.php");
        exit();
    }

    // vérification si l'image à été envoyée 
    if($_FILES['fichier']["error"]==0)
    {
         // récup des infos de l'image (nom, extension, type, taille)
            $nomImage = basename($_FILES['fichier']['name']);
            $extension = strtolower((string) strrchr($_FILES['fichier']['name'], '.'));
            $mimeType = detectUploadedMimeType($_FILES['fichier']['tmp_name'], $_FILES['fichier']['type']);
            $size = (int) filesize($_FILES['fichier']['tmp_name']);

            $dossier = "../images/";
            $errImg = 0;

            $extensionsAcceptees = ['.jpg', '.jpeg', '.png', '.gif'];
            $mimeTypesAcceptes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

            if (!in_array($extension, $extensionsAcceptees, true)) {
                $errImg = 5;
            } elseif (!in_array($mimeType, $mimeTypesAcceptes, true)) {
                $errImg = 6;
            } elseif ($size <= 0) {
                $errImg = 4;
            } elseif ($size > UPLOAD_MAX_BYTES) {
                $errImg = 7;
            }


            if($errImg==0)
            {
                // traitement du nom du fichier
                // corriger les risques de caractères spéciaux dans le nom de fichier
                $nomImageLisible = strtr($nomImage, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $nomImageSafe = preg_replace('/([^.a-z0-9]+)/i', '-', $nomImageLisible);
                $uniqnomSafe = uniqid().'-'.$nomImageSafe;

                if(move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier.$uniqnomSafe))
                {
                    // insertion dans la bdd
                    
                    /**
                     * @var PDO $bdd 
                     */
                    $insert = $bdd->prepare("INSERT INTO images(fichier,id_product) VALUES(:fichier,:myid)");
                    $insert->execute([
                        ":fichier" => $uniqnomSafe,
                        ":myid" => $id
                    ]);
                    header("LOCATION:updateProduct.php?id=".$id);
                    exit();
                }else{
                    header("LOCATION:addImg.php?id=".$id."&errorImg=8");
                    exit();
                }

            }else{
                header("LOCATION:addImg.php?id=".$id."&errorImg=".$errImg);
                exit();
            }
    }else{
        header("LOCATION:addImg.php?id=".$id."&errorImg=".$_FILES['fichier']['error']);
        exit();
    }

?>