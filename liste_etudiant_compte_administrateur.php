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
    <link href="res/css/liste_etudiant_compte_administrateur.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Liste des élèves</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
    </div>
    <div class="maPopup" id="popup_ajouter">
        <div class="contenu_popup">
            <span class="fermer"><img src=" res/img/x-circle.svg " alt="croix"></span>
            <h4>Ajouter/modifier un eleve :</h4>
            <table>
                <form action="">
                    <tr>
                        <td>
                            Nom:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Prénom:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                </form>
            </table>
            <br>
            <button id="valider"> VALIDER</button>
        </div>
    </div>

    <script type="text/javascript" src="res/js/page_ajouter_modifier_supprimer_competence_professeur.js"></script>
    <div id="wrapper">
        <br>
        <h1> <strong>LISTE DES ELEVES</strong></h1>
        <br>
        <h2><strong>ING 2</strong></h2>
        <br>
        <h4>LES ELEVES : </h4>
        <div class="align_bt">
            <button id="bt_ajouter"><img src="res/img/plus-lg.svg" alt="icon" class="img_plus"><strong><span
                        class="text_ajouter">AJOUTER</span></button>
        </div>
        <table>
            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Emma smith
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>

                                Lucas Johnson
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Olivia Brown
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Liam Taylor
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Ava Miller
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Noah Anderson
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Isabella Martinez
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Sophia Davis
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Benjamin Wilson

                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="liste_eleves">
                        <ul>
                            <li>
                                Mia Thompson


                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <button class="bt_modifier">MODIFIER</button>
                    <button class="bt_supprimer">SUPPRIMER</button>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>