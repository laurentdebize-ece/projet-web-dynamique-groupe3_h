<?php

require_once 'src/session.php';
require_once 'src/database/models/Competence.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);

//FIXME: j'imagine déja Debize en train d'halluciner avec des yeux injectés de sang tellement c moche
function getSkills(DatabaseController $db, ?string $type, int $uid)
{
    $matieres = Matiere::getAllSubjectsUser($db, $uid);
    $competences = [];
    foreach ($matieres as $matiere) {
        $skills = Competence::getCompetencesByMatiere($db, $matiere[1]);
        foreach ($skills[$type] as $skill) {
            $competences[] = [$skill, $matiere[1], $matiere[0]];
        }
    }
    return $competences;
}

function drawCompetences(array $skills, int $uid)
{
    if (count($skills) > 0) {
        echo "<div class=\"liste_competences\">";
        foreach ($skills as $matiere) {
            [[$nomCompetence, $idCompetence], $idMat, $nomMat] = $matiere;
            $evaluation = Evaluation::getEvaluationForCompetence(DatabaseController::getInstance(), $uid, $idCompetence, $idMat);
            echo "<div class=\"card card-body competence\">";
            echo "<p class=\"skillName\">" . $nomCompetence . " <span class=\"skillSubject\">($nomMat)</span> </p>";
            if (!is_null($evaluation)) {
                $eval = $evaluation->getEvaluationString();
                $autoEvals = ['skillA', 'skillECA', 'skillNA'];
                $cssClass = $autoEvals[$evaluation->getEvaluation() - 1];
                echo "<p class=\"skillPill $cssClass\">" . $eval . "</p>";
                echo "<p class=\"skillEvalDate\"> Noté le " . $evaluation->getDate()->format('d/m/Y') . "</p>";
            } else {
                echo "<p class=\"skillPill\">Pas encore évalué<p>";
                echo "<button class=\"btn btn-primary\" onclick=\"showEvalModal($idCompetence, $idMat)\">Evaluer</button>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
}

if (isset($_POST['action']) && isset($_POST['skill']) && isset($_POST['evaluation']) && isset($_POST['matid'])) {
    $matiere = (int)$_POST['matid'];
    $skill = $_POST['skill'];
    $eval = (int)$_POST['evaluation'];

    //TODO: SECU MONO EVAL
    $eval = new Evaluation(new DateTime('now', new DateTimeZone('Europe/Paris')), $sess->getUser()->getID(), $skill, $matiere, $eval);
    Evaluation::insert(DatabaseController::getInstance(), $eval);
    header("Location: /competences.php");
    exit();
}

$skills = getSkills(DatabaseController::getInstance(), Competence::COMPETENCE_TYPE_MATIERE, $sess->getUser()->getID());
$skills = array_merge($skills, getSkills(DatabaseController::getInstance(), Competence::COMPETENCE_TYPE_TRANSVERSE, $sess->getUser()->getID()));

switch ($_GET['sort'] ?? 'name') {
    case 'name':
        usort($skills, function ($a, $b) {
            return strcmp($a[0][0], $b[0][0]);
        });
        break;
    case 'name_rev':
        usort($skills, function ($a, $b) {
            return strcmp($b[0][0], $a[0][0]);
        });
        break;

    case 'eval':
        usort($skills, function ($a, $b) {
            $evalA = Evaluation::getEvaluationForCompetence(DatabaseController::getInstance(), SessionManager::getInstance()->getUser()->getID(), $a[0][1], $a[1]);
            $evalB = Evaluation::getEvaluationForCompetence(DatabaseController::getInstance(), SessionManager::getInstance()->getUser()->getID(), $b[0][1], $b[1]);
            if (is_null($evalA)) {
                return 1;
            } else if (is_null($evalB)) {
                return -1;
            } else {
                return $evalA->getEvaluation() - $evalB->getEvaluation();
            }
        });
        break;

    default:
        break;
}

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
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="res/css/matiere.css" rel="stylesheet">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <script src="res/js/skill.js"></script>
    <title>Toutes mes compétences</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a href="/competences.php"><button class="toolbar-btn"><strong>Mes compétences</strong></button></a>
    </div>
    <div id="wrapper">
        <br>
        <h1> <span id="titrePage">TOUTES MES COMPETENCES</span></h1>
        <div id="emplacement_filtres">
            <!-- <button class="btn btn-primary">🔎 FILTRES</button> -->

        </div>
        <div class="liste_competences">
            <div class="btn-group" role="group" aria-label="filtre">
                <button class="btn btn-primary active">Trier par:</button>
                <a href="/competences.php?sort=name" class="btn btn-primary">Nom (Alphabetique)</a>
                <a href="/competences.php?sort=name_rev" class="btn btn-primary">Nom (Inverse)</a>
                <a href="/competences.php?sort=eval" class="btn btn-primary">Note</a>
                <!-- <a href="/competences.php.php?filter=" class="btn btn-primary">Date d'evaluation</a> -->
            </div>
            <?php
            drawCompetences($skills, $sess->getUser()->getID());
            ?>
        </div>
    </div>
    <div class="modal modal-l fade" tabindex="-1" id="addevalmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remplir une auto-évaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="matid" value="matid" id="mat_id">
                        <input type="hidden" name="action" value="add_eval">
                        <input type="hidden" name="skill" value="id_skill" id="skill_id">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="evaluation" id="a" autocomplete="off" value="1" checked>
                            <label class="btn btn-outline-success" for="a">Acquis</label>

                            <input type="radio" class="btn-check" name="evaluation" id="eca" autocomplete="off" value="2">
                            <label class="btn btn-outline-warning" for="eca">En cours d'acquisition</label>

                            <input type="radio" class="btn-check" name="evaluation" id="na" autocomplete="off" value="3">
                            <label class="btn btn-outline-danger" for="na">Non Acquis</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                        <input type="submit" class="btn btn-primary" value="Valider">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>