<?php
header("no-cache, no-store, must-revalidate");
require 'src/session.php';
// require 'src/database/models/Matiere.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/matiereEtudiant.css" type="text/css">
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <script src="res\js\jquery-3.7.0.min.js"></script>
    <script type="text/javascript" src="res/js/matiereEtudiant.js"></script>
    <title>SkillTracker - Mes matières</title>
</head>

<body class="background">
    <div id="header">
        <table>
            <tr>
                <img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo">
            </tr>
            <tr>
                <button class="b1"> <strong>Mon compte</strong></button>
            </tr>
            <tr>
                <button class="b1"> <strong>Mes compétences</strong></button>
            </tr>
            <tr>
                <button class="b1"> <strong>A propos</strong></button>
            </tr>
            <tr>
                <button class="b1"> <strong>Contact</strong></button>
            </tr>
        </table>
    </div>

    <h1 id="texte">
        MES MATIERES
    </h1>


    <div class="ensemble-fleche">
        <img src="res/img/fleche_gauche.svg" class="img-svg1">
        <div class="slide">

            <?php
            $matieres = Matiere::getAllSubjectsUser(DatabaseController::getInstance(), $sess->getUser()->getID());
            foreach ($matieres as $matiere) {
                echo "<a href=\"$matiere[1]\">
                <span class=\"matiere\">
                    <img src=\"res/img/imageMatiere.png\">
                    <br><br>
                    <div id=\"texte\">
                        " . $matiere[0] . "
                    </div>
                </span>
                </a>";
            }
            ?>
        </div>
        <img src="res/img/fleche_droite.svg" class="img-svg2">
    </div>
</body>

</html>