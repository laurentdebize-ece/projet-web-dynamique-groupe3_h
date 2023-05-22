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
    <link href="res/css/liste_profeseur_compte_administrateur.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Liste des professeurs</title>
</head>

<body>
    <div class="maPopup" id="popup_ajouter">
        <div class="contenu_popup">
            <span class="fermer"><img src="res/img/x-circle.svg" alt="croix"></span>
            <h4>Ajouter/modifier un professeur :</h4>
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
                    <tr>
                        <td>
                            classe:
                        </td>
                        <td>
                            <input type="text" name="intitulé" id="intitulé">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Matière
                        </td>
                        <td>
                            <select name="eval" id="eval">
                                <option value="classe1">Matière1</option>
                                <option value="classe2">Matière2</option>
                                <option value="classe3">matière3</option>
                                <option value="classe3">matière4</option>
                                <option value="classe3">matière5</option>
                                <option value="classe3">matière6</option>
                            </select>
                        </td>
                    </tr>
                        
                    
                </form>
            </table>
            <br>
            <button id="valider"> VALIDER</button>
        </div>
    </div>
    
    
    <script type="text/javascript" src="res/js/page_ajouter_modifier_supprimer_competence_professeur.js"></script>
    <div id="header">
        <table>
            <tr>
                <img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo">
            </tr>
            <tr>
                <button class="b1"> <strong>Mon compte</strong></button>
            </tr>
            <tr>
                <a href="page_mes_competences.php"><button class="b1"><strong>Mes compétences</strong></button></a>
            </tr>
            <tr>
                <button class="b1"> <strong>A propos</strong></button>
            </tr>
            <tr>
                <button class="b1"> <strong>Contact</strong></button>
            </tr>
        </table>
    </div>
    <div id="wrapper">
        <br>
        <h1> <strong>LISTE DES PROFESSEURS</strong></h1>
        <br>
        
        <h4>LES PROFESSEURS : </h4>
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