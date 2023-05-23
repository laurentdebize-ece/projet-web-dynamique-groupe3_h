<?php
header("no-cache, no-store, must-revalidate");
require_once "src/session.php";
require_once "src/database/models/Cours.php";
$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_PROF);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <link rel="stylesheet" href="res/css/nettoyageStrong.css" type="text/css">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="res/css/classes.css" type="text/css">
    <link rel="icon" type="icon_onglet.png" href="icon_onglet.png">
    <title>Classes</title>
</head>

<body class="background">
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/classes.php"><button class="toolbar-btn">Mes classes</button></a>
    </div>
    <h1 id="texte2">
        <span id="gras">
            MES CLASSES :
        </span>
    </h1>

    <div class="ensemble-fleche">
        <div class="slide">
            <?php
            $classes = Cours::getClassesForProfesseur(DatabaseController::getInstance(), $sess->getUser()->getId());
            foreach ($classes as $classe) {
                $nomClasse = $classe['numGroupe'];
                $idClasee = $classe['idClasse'];
                echo "<div class=\"classe\">
                <table>
                <tr>
                    <td>
                        <img src=\"res/img/imageClasseProfesseur.png\" width=\"156\" height=\"120\">
                    </td>
                    <td>
                        <div id=\"texte\">Classe<br>
                            <p>$nomClasse</p>
                        </div>
                    </td>
                    <td>
                        <div id=\"espace\">
                        </div>
                    </td>

                    <td>
                    <a href=\"/evaluation.php?class=$idClasee\">
                        <div id=\"bouton\">
                            <div id=\"texte2\">
                                Accèder
                            </div>
                        </div>
                        </a>
                    </td>
                </tr>
            </table>
        </div>";
            }
            ?>
        </div>
    </div>


</body>

</html>