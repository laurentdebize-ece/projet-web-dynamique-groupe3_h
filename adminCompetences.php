<?php
header("no-cache, no-store, must-revalidate");

require_once 'src/session.php';

$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_ADMIN);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="res/css/page_auto_ajouter_competences.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="res/css/bootstrap.min.css" rel="stylesheet">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script type="text/javascript" src="res/js/page_auto_ajouter_competences.js"></script>
    <title>Ajouter compétences</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/users.php"><button class="toolbar-btn"> <strong>Utilisateurs</strong></button></a>
        <a href="/"><button class="toolbar-btn"> <strong>Compétences</strong></button></a>
        <a><button class="toolbar-btn"> <strong>Ecoles</strong></button></a>
        <div class="toolbar-logout">
            <a href="/logout.php"><button class="toolbar-btn"> <strong>Se déconnecter</strong></button></a>
        </div>
    </div>
    <div id="wrapper">
        <br>
        <h1> <strong> <span id="titrePage">AJOUTER DES COMPETENCES</span> </strong></h1>
        <div id="emplacement_filtres">
            <button class="ajouter"><img src="plus-lg.svg" alt="icon" class="img_plus"><strong><span class="text_ajouter">AJOUTER</span></strong></button>
        </div>
        <div id="navigation">
            <br>
            <div class="matiere">
                <div class="matieres">
                    <h4>COMPETENCES AJOUTEES : </h4>
                    <div class="liste_competences">
                        <ul>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="maPopup" id="popup_ajouter">
        <div class="contenu_popup">
            <span class="fermer"><img src="x-circle.svg" alt="croix"></span>
            <h4>Ajouter une compétence :</h4>
            <table>
                <form action="">
                    <tr>
                        <td>
                            <label for="intitulé">Intitulé:</label>
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Evaluation:
                        </td>
                        <td>
                            <select name="eval" id="eval">
                                <option value="A">A</option>
                                <option value="ECA">ECA</option>
                                <option value="NA">NA</option>
                            </select>
                        </td>
                    </tr>
                </form>
            </table>
            <br>
            <input type="submit" id="valider" value="VALIDER">
        </div>
    </div>
</body>

</html>