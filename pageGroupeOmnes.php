<?php
header("no-cache, no-store, must-revalidate");
require 'src/session.php';
require_once 'src/database/models/Classe.php';
require_once 'src/database/models/Filiere.php';
require_once 'src/database/models/Promotion.php';
require_once 'src/database/models/Ecole.php';
require_once 'src/database/models/Competence.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);
$idUser = $sess->getUser()->getId();
$user = User::select(DatabaseController::getInstance(), null, ["WHERE `idUser` = $idUser","LIMIT 1"])->fetch();
$idClasse = intval($user['idClasse']);
$classe = Classe::select(DatabaseController::getInstance(), null, ["WHERE `idClasse` = $idClasse","LIMIT 1"])->fetch();
$idPromo = intval($classe['idPromo']);
$promo = Promotion::select(DatabaseController::getInstance(), null, ["WHERE `idPromo` = $idPromo","LIMIT 1"])->fetch();
$idFiliere = intval($promo['idFiliere']);
$filiere = Filiere::select(DatabaseController::getInstance(), null, ["WHERE `idFiliere` = $idFiliere","LIMIT 1"])->fetch();
$idEcole = intval($filiere['idEcole']);
$ecole = Ecole::select(DatabaseController::getInstance(), null, ["WHERE `idEcole` = $idEcole","LIMIT 1"])->fetch();

$competences = Competence::getCompetencesCurrentMonth(DatabaseController::getInstance());
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="res/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="res/css/toolbar.css" rel="stylesheet" type="text/css" />
    <link href="res/css/pagegroupeOmnes.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="res/img/icon_onglet.png" href="icon_onglet.png">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <title>Groupe OMNES</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a href="/competences.php"><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
        <a href="/pageGroupeOmnes.php"><button class="toolbar-btn"> <strong>My Omnes</strong></button></a>
        <div class="toolbar-logout">
            <a href="/logout.php"><button class="toolbar-btn"> <strong>Se déconnecter</strong></button></a>
        </div>
    </div>
    <div id="wrapper">
        <h1>MES INFORMATIONS : GROUPE OMNES </h1>
        <div class="container">
            <div class="col-lg-4">
                <div class="content_col_1">
                    <h3>DERNIERES COMPETENCES AJOUTEES</h3>
                    <?php
                    $competences = Competence::getCompetencesCurrentMonth(DatabaseController::getInstance());
                    ?>

                    <ul>
                        <?php foreach ($competences as $competence) : ?>
                            <li><?php echo $competence; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>COMPETENCES POPULAIRES</h3>
                    <?php
                    $competences = Competence::getPopularCompetenceByEval(DatabaseController::getInstance());
                    ?>

                    <ul>
                        <?php foreach ($competences as $competence) : ?>
                            <li><?php echo $competence; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
            <div class="content_col_2">
                <?php
                echo "<table border='1' align='center'>";
                ?>
                <thead>
                    <tr>
                        <th>Ecole</th>
                        <th>Filiere</th>
                        <th>Promo</th>
                        <th>Classe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $ecole['nomEcole']; ?></td>
                        <td><?php echo $filiere['nomFiliere']; ?></td>
                        <td><?php echo $promo['annee']; ?></td>
                        <td><?php echo $classe['numGroupe']; ?></td>
                    </tr>
                </tbody>
                </table>
                <img src="res/img/ECE_LOGO.png" alt="img" id="logo_ece">
            </div>
            </div>
        </div>
    </div>
</body>

</html>