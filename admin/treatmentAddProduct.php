<?php
require __DIR__ . '/includes/upload-limits.php';
require __DIR__ . '/includes/auth.php';

    // vérification de l'envoie du formulaire'
    if(isset($_POST['nom']))
    {
        // init de la variable d'erreur si pas 0 c'est qu'il eu une erreur
        $err = 0;

        // vérif de chaque donnée
        // vérification si le champ nom est vide
        if(empty($_POST['nom']))
        {
            // si c'est le cas, on modifie la variable d'erreur avec un numéro qui fera office de code d'erreur
            $err= 1;
        }else{
            // si pas vide, on traite la donnée, avec l'aide de htmlspecialchars qui va transforme les caractères spéciaux en entité HTML
            $nom = htmlspecialchars($_POST['nom']);
        }

        if(empty($_POST['date']))
        {
            $err= 2;
        }else{
            $date = htmlspecialchars($_POST['date']);
        }

        if(empty($_POST['description']))
        {
            $err= 3;
        }else{
            $description = htmlspecialchars($_POST['description']);
        }

        if(empty($_POST['categorie']))
        {
            $err= 4;
        }else{
            $categorie = htmlspecialchars($_POST['categorie']);
        }

        // pour l'upload de l'image c'est plus la même chose que pour le nom, plus du $_POST mais du $_FILES
        /*if(empty($_POST['cover']))
        {
            $err= 5;
        }else{
            $cover = htmlspecialchars($_POST['cover']);
        }*/

        // si toutes les données (string/number,...) sont correctes on peut continuer (et traiter l'image)
        // si jamais eu d'erreur, la variable $err est elle à 0 sinon il y a un code erreur à la place
        if($err == 0)
        {
            // traitement de l'image
            // si erreur 0 alors c'est qu'il n'y a pas d'erreur
            // si erreur 1 alors c'est qu'il y a eu une erreur de poids de fichier via php.ini
            // si erreur 2 alors c'est qu'il y a eu une erreur de poids de fichier via MAX_FILE_SIZE dans le formulaire
            // si erreur 3 alors c'est qu'il y a eu une erreur de transfert partiel du fichier
            // si erreur 4 alors c'est qu'il y a eu une erreur de transfert du fichier ou pas de fichier du tout
            if($_FILES['cover']['error'] == 0)
            {
                // récup des infos de l'image (nom, extension, type, taille)
                $nomImage = basename($_FILES['cover']['name']);
                $extension = strtolower((string) strrchr($_FILES['cover']['name'], '.'));
                $mimeType = detectUploadedMimeType($_FILES['cover']['tmp_name'], $_FILES['cover']['type']);
                $size = (int) filesize($_FILES['cover']['tmp_name']);

                // le dossier de destination (attention au dernier /)
                $dossier = "../images/";
                // initialisation de $errImg à 0 (pas d'erreur)
                $errImg = 0;

                $extensionsAcceptees = ['.jpg', '.jpeg', '.png'];
                $mimeTypesAcceptes = ['image/jpeg', 'image/jpg', 'image/png'];

                if (!in_array($extension, $extensionsAcceptees, true)) {
                    $errImg = 5;
                } elseif (!in_array($mimeType, $mimeTypesAcceptes, true)) {
                    $errImg = 6;
                } elseif ($size <= 0) {
                    $errImg = 4;
                } elseif ($size > UPLOAD_MAX_BYTES) {
                    $errImg = 7;
                }

                // vérification des erreurs personnalisées de l'upload de l'image
                if($errImg == 0)
                {
                    // pas d'erreur au niveau de l'upload de l'image
                    // traitement du nom de fichier de l'image
                    // risque d'erreur si caractères spéciaux et espaces dans le nom de fichier (à cause du serveur Linux)
                    // risque de conflit avec les noms identiques de fichier

                    // corriger les risques de caractères spéciaux dans le nom de fichier
                    $nomImageLisible = strtr($nomImage, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $nomImageSafe = preg_replace('/([^.a-z0-9]+)/i', '-', $nomImageLisible);
                    $uniqnomSsafe = uniqid().'-'.$nomImageSafe;

                    // déplacement de l'image dans le dossier de destination
                    // avec la fonction move_uploaded_file()
                        // elle retourne/réponds vrai si c'est déplacé ou faux s'il y a eu une erreur
                        // attention que c'est le fichier donc le fichier temporaire qu'on déplace et lui donne le nom $uniqnomSsafe et dans le dossier de destination
                    // move_uploaded_file($_FILES['cover']['tmp_name'],$dossier.$uniqnomSsafe);
                    // $dossier.$uniqnomSsafe ça donne quoi?
                        // . concaténation
                        // $dossier = ../images/
                        // . concaténation
                        // $uniqnomSsafe = 123456789-nom-de-l-image.jpg
                            // ../images/123456789-nom-de-l-image.jpg
                    if(move_uploaded_file($_FILES['cover']['tmp_name'],$dossier.$uniqnomSsafe))
                    {
                        // insertion dans la base de données
                        require "../config/connexion.php";
                        /**
                         * @var $bdd PDO
                         */
                        $insert = $bdd->prepare("INSERT INTO products(name,date,category,description,cover) VALUE(:nom,:date,:category,:descri,:cover)");
                        $insert->execute([
                            ":nom" => $nom,
                            ":date"=>$date,
                            ":category"=>$categorie,
                            ":descri"=>$description,
                            ":cover"=>$uniqnomSsafe
                        ]);
                       $extensionLower = strtolower($extension);
                       if (in_array($extensionLower, ['.jpg', '.jpeg', '.png'], true)) {
                           header('LOCATION:resizeCover.php?image=' . urlencode($uniqnomSsafe));
                           exit();
                       }

                       header('LOCATION:products.php?add=success');
                       exit();
                    }else{
                        // il y a eu un problème au niveau du déplacement de l'image donc erreur avec indication
                        header("LOCATION:addProduct.php?errorImg=8");
                        exit();
                    }

                }else{
                    $redirect = 'LOCATION:addProduct.php?errorImg=' . $errImg;
                    if ($errImg === 7) {
                        $redirect .= '&size=' . $size;
                    }
                    header($redirect);
                    exit();
                }

            }else{
                header('LOCATION:addProduct.php?errorImg=' . (int) $_FILES['cover']['error']);
                exit();
            }

        }else{
            // si il y a une erreur, on redirige vers la page d'ajout avec l'indication de l'erreur (en mode GET ?error=1)
            header("LOCATION:addProduct.php?error=".$err);
            exit();
        }

    }else{
        // si pas de post on redirige vers la page d'accueil donc pas passé par le formulaire
        header("LOCATION:index.php");
        exit();
    }
?>