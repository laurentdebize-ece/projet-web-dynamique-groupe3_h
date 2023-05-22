<?php
header("no-cache, no-store, must-revalidate");

require_once 'src/session.php';
require_once 'src/database/models/Competence.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);

function drawCompetences(SessionManager $sess, int $idMat, array $competences, string $typesCompetences, string $titre)
{
    if (count($competences[$typesCompetences]) > 0) {
        echo "<h4>$titre</h4>";
        echo "<div class=\"liste_competences\">";
        foreach ($competences[$typesCompetences] as $competence) {
            [$nomCompetence, $idCompetence] = $competence;
            $evaluation = Evaluation::getEvaluationForCompetence(DatabaseController::getInstance(), $sess->getUser()->getId(), $idCompetence, $idMat);
            echo "<div class=\"card card-body competence\">";
            echo "<p><b>$nomCompetence</b></p>";
            if (!is_null($evaluation)) {
                $autoEvals = ['a' => 'skillA', 'eca' => 'skillECA', 'na' => 'skillNA'];
                $note = ['a' => 'Acquis', 'eca' => 'En cours d\'acquisition', 'na' => 'Non Acquis'];
                $eval = $note[$evaluation->getEvaluation()];
                $val = $autoEvals[$evaluation->getEvaluation()];
                echo "<p class=\"skillPill $val\">" . $eval . "</p>";
            } else {
                echo "<p class=\"skillPill\">Pas encore évalué<p>";
                echo "<button class=\"btn btn-primary\" onclick=\"showEvalModal($idCompetence)\">Evaluer</button>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
}

///TODO: REGARDER LE PATTERN PRG 
/// Verif que l'ID matière est bien défini.
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header('Location: /listing.php');
    exit();
}

$mat_id = $_GET['id'];
$competences = Competence::getCompetencesByMatiere(DatabaseController::getInstance(), $mat_id);

if (isset($_POST['action']) && isset($_POST['skill']) && isset($_POST['evaluation'])) {
    $matiere = $_POST['id'];
    $skill = $_POST['skill'];
    $eval = $_POST['evaluation'];

    //TODO: SECU MONO EVAL
    $eval = new Evaluation($eval, $sess->getUser()->getID(), $skill, $matiere);
    Evaluation::insert(DatabaseController::getInstance(), $eval);
    header("Location: /showMatiereCompetences.php?id=" . $mat_id);
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
    <link href="page_evaluation_competences_etudiant.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="res/css/showMatiereCompetences.css" rel="stylesheet">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <script src="res/js/skill.js"></script>
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
        drawCompetences($sess, $mat_id, $competences, Competence::COMPETENCE_TYPE_MATIERE, 'Compétences de la matière');
        drawCompetences($sess, $mat_id, $competences, Competence::COMPETENCE_TYPE_TRANSVERSE, 'Compétences transverses');
        ?>
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
                        <input type="hidden" name="id" value="<?php echo $mat_id ?>">
                        <input type="hidden" name="action" value="add_eval">
                        <input type="hidden" name="skill" value="id_skill" id="skill_id">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="evaluation" id="a" autocomplete="off" value="a" checked>
                            <label class="btn btn-outline-success" for="a">Acquis</label>

                            <input type="radio" class="btn-check" name="evaluation" id="eca" autocomplete="off" value="eca">
                            <label class="btn btn-outline-warning" for="eca">En cours d'acquisition</label>

                            <input type="radio" class="btn-check" name="evaluation" id="na" autocomplete="off" value="na">
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