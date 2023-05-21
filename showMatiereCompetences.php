<?php

require_once 'src/session.php';
require_once 'src/database/models/Competence.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);

function drawCompetences(SessionManager $sess, array $competences, string $typesCompetences, string $titre)
{
    if (count($competences[$typesCompetences]) > 0) {
        echo "<h4>$titre</h4>";
        echo "<div class=\"liste_competences\">";
        foreach ($competences[$typesCompetences] as $competence) {
            [$nomCompetence, $idCompetence] = $competence;
            $evaluation = Evaluation::getEvaluationForCompetence(DatabaseController::getInstance(), $sess->getUser()->getId(), $idCompetence);
            echo "<div class=\"card card-body competence\">";
            echo "<p><b>$nomCompetence</b></p>";
            if (!is_null($evaluation)) {
                echo "Evaluation : " . $evaluation->getEvaluation();
            } else {
                echo "<i> Pas encore évalué </i>";
                echo "<a href=\"evaluation.php?id=$idCompetence\"><button class=\"btn btn-primary\">Evaluer</button></a>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
}

/// Verif id
if (!isset($_GET['id'])) {
    header('Location: /listing.php');
    exit();
}

$matieres = Competence::getCompetencesByMatiere(DatabaseController::getInstance(), $_GET['id']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <link href="page_evaluation_competences_etudiant.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="res/css/showMatiereCompetences.css" rel="stylesheet">
    <title>Evaluation</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
    </div>


    <div id="wrapper">
        <br>
        <h1> <strong>EVALUATION DES COMPETENCES</strong></h1>
        <h2><strong><span class="titreMatiere">MATHEMATIQUES</span></strong></h2>
        <br>

        <?php
        drawCompetences($sess, $matieres, Competence::COMPETENCE_TYPE_MATIERE, 'Compétences de la matière');
        drawCompetences($sess, $matieres, Competence::COMPETENCE_TYPE_TRANSVERSE, 'Compétences transverses');
        ?>
    </div>
</body>

</html>