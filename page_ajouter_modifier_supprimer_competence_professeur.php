<?php
$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="res/css/page_ajouter_modifier_supprimer_competence_professeur.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Evaluation</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
    </div>
    <div class="maPopup" id="popup_ajouter">
        <div class="contenu_popup">
            <span class="fermer"><img src="res/img/x-circle.svg" alt="croix"></span>
            <h4>Ajouter/modifier une compétence :</h4>
            <table>
                <form action="">
                    <tr>
                        <td>
                            titre:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Date:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    <tr>
                        <td>
                            Description:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Classe:
                        </td>
                        <td>
                            <select name="eval" id="eval">
                                <option value="classe1">classe1</option>
                                <option value="classe2">clase2</option>
                                <option value="classe3">classe3</option>
                                <option value="classe3">classe4</option>
                                <option value="classe3">classe5</option>
                                <option value="classe3">classe6</option>
                            </select>
                        </td>
                    </tr>


                </form>
            </table>
            <br>
            <button id="valider"> valider</button>
        </div>
    </div>
    </div>
    <script type="text/javascript" src=" "></script>
    <div id="wrapper">
        <br>
        <h1> <strong>EVALUATION DES COMPETENCES</strong></h1>
        <br>
        <div>
            <h2><strong><span class="titreMatiere">MATHEMATIQUES</span></strong></h2>
            <div class="align_bt">
                <button id="bt_ajouter"><img src="res/img/plus-lg.svg" alt="icon" class="img_plus"><strong><span
                            class="text_ajouter">AJOUTER</span></button>
            </div>

            <br>
            <h4>MODIFIER CES COMPETENCES</h4>


            <div class="liste_competences">
                <ul>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>

                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>

                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>

                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>

                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                    <li>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                        <button class="bt_modifier">modifier</button>
                        <button class="bt_supprimer">supprimer</button>
                    </li>
                </ul>
            </div>
        </div>
</body>

</html>