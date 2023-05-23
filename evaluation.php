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

if (!isset($_GET['class'])) {
    header('Location: /classes.php');
    exit();
}

$evals = Evaluation::getEvaluationsForProf(DatabaseController::getInstance(), $sess->getUser()->getId(), $classe);

if (isset($_POST['action']) && isset($_POST['eval_id']) && isset($_POST['evaluation'])) {
    $id = (int)$_POST['eval_id'];
    $note = (int)$_POST['evaluation'];

    $eval = Evaluation::select(DatabaseController::getInstance(), null, ["WHERE idEvaluation = $id"])->fetchTyped();
    $eval->validateEval($note);
    Evaluation::modify(DatabaseController::getInstance(), $eval);
    header('Location: /evaluation.php?class=' . $classe);
    exit();
}
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
    <script src="res/js/evaluation.js"></script>
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <title>Gestion des évaluations</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/classes.php"><button class="toolbar-btn">Mes classes</button></a>
        <div class="toolbar-logout">
            <a href="/logout.php"><button class="toolbar-btn"> <strong>Se déconnecter</strong></button></a>
        </div>
    </div>

    <h1> Evaluations en attente de validation </h1>
    <div class="liste_evaluations">
        <?php
        foreach ($evals as $eval) {
            $nomC = $eval['nomCompetences'];
            $nomMat = $eval['nomMatiere'];

            $noteTexts = ['Acquis', 'En cours d\'acquisition', 'Non acquis'];
            $cssclasses = ['skillA', 'skillECA', 'skillNA'];
            $class = $cssclasses[$eval['AutoEvaluation'] - 1];
            $text = $noteTexts[$eval['AutoEvaluation'] - 1];

            $evalVal = $eval['AutoEvaluation'];

            $idEval = $eval['idEvaluation'];

            $userName = $eval['prenomUser'] . ' ' . $eval['nomUser'];

            echo "<div class=\"card card-body evaluation\">";
            echo "<h5>$userName</h5>";
            echo "<p class=\"skillName\">$nomC <span class=\"skillSubject\">($nomMat)</span></p>";
            echo "<p class=\"skillPill $class\">$text</p>";
            echo "<button class=\"btn btn-primary\" onclick=\"showValModal($idEval, $evalVal)\"> Valider </button>";
            echo "</div>";
        }

        if (count($evals) == 0) {
            echo "<div class=\"card card-body\"><h2> Pas d'évaluations en attente </h2></div>";
        }
        ?>
    </div>

    <div class="modal modal-l fade" tabindex="-1" id="validatemodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Valider une auto-évaluation étudiant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/evaluation.php?class=<?php echo $classe ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="validate">
                        <input type="hidden" name="eval_id" id="id">
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