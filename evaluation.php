<?php

require_once 'src/session.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_PROF);

if (!isset($_GET['class'])) {
    header('Location: /classes.php');
    exit();
}


/// fixme: en théorie on cassera rien même avec une classe qui n'existe pas???
$classe = (int)$_GET['class'];

$evals = Evaluation::getEvaluationsForProf(DatabaseController::getInstance(), $sess->getUser()->getId(), $classe);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <link rel="stylesheet" href="res/css/evaluation.css" />
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <title>Gestion des évaluations</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/classes.php"><button class="toolbar-btn">Mes classes</button></a>
    </div>

    <div class="liste_evaluations">
        <?php
        foreach ($evals as $eval) {
            $nomC = $eval['nomCompetences'];
            $nomMat = $eval['nomMatiere'];

            $noteTexts = ['Acquis', 'En cours d\'acquisition', 'Non acquis'];
            $cssclasses = ['skillA', 'skillECA', 'skillNA'];
            $class = $cssclasses[$eval['AutoEvaluation'] - 1];
            $text = $noteTexts[$eval['AutoEvaluation'] - 1];

            $userName = $eval['prenomUser'] . ' ' . $eval['nomUser'];

            echo "<div class=\"card card-body evaluation\">";
            echo "<h5>$userName</h5>";
            echo "<p class=\"skillName\">$nomC <span class=\"skillSubject\">($nomMat)</span></p>";
            echo "<p class=\"skillPill $class\">$text</p>";
            echo "<button class=\"btn btn-primary\"> Valider </button>";
            echo "</div>";
        }
        if (count($evals) == 0) {
            echo "<div class=\"card card-body\"><h2> Pas d'évaluations en attente </h2></div>";
        }
        ?>
    </div>

</body>

</html>