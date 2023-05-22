<?php

require_once 'src/session.php';
require_once 'src/database/models/Competence.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="icon" href="res/img/icon_onglet.png">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <link href="page_evaluation_competences_etudiant.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="res/css/showMatiereCompetences.css" rel="stylesheet">
    <title>Toutes mes compétences</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a><button class="toolbar-btn"><strong>Mes compétences</strong></button></a>
    </div>
    <div id="wrapper">
        <br>
        <h1> <strong> <span id="titrePage">TOUTES MES COMPETENCES</span> </strong></h1>
        <div id="emplacement_filtres">
            <button class="filtres"><img src="filter.svg" alt="icon"> FILTRES</button>
        </div>
        <div class="liste_competences">
            <?php
            $competences = MatiereCompetences::getSubjectCompetencesUser(DatabaseController::getInstance(), $sess->getUser()->getID());
            var_dump($competences);
            ?>
        </div>
    </div>
</body>

</html>