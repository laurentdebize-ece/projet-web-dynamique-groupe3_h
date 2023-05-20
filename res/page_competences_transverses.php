<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="page_competences_transverses.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="icon_onglet.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Compétences transverses</title>
</head>

<body>
    <script type="text/javascript" src="page_competences_transverses.js"></script>
    <div class="maPopup" id="popup">
        <div class="contenu_popup">
            <span class="fermer"><img src="x-circle.svg" alt="croix"></span>
            <h4>Filtres :</h4>
            <table>
                <form action="">
                    <tr>
                        <td>
                            Ordre alphabétique:
                        </td>
                        <td>
                            <input type="radio" name="alphabétique" id="alpha_croissant">
                            <label for="alpha_croissant">Croissant</label>
                        </td>
                        <td>
                            <input type="radio" name="alphabétique" id="alpha_decroissant">
                            <label for="alpha_decroissant">Décroissant</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Date:
                        </td>
                        <td>
                            <input type="radio" name="date" id="date_croissante">
                            <label for="date_croissante">Croissante</label>
                        </td>
                        <td>
                            <input type="radio" name="date" id="date_decroissante">
                            <label for="date_decroissante">Décroissante</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Statut de la compétence:
                        </td>
                        <td>
                            <input type="radio" name="statut" id="A">
                            <label for="A">Acquis</label>
                        </td>
                        <td>
                            <input type="radio" name="statut" id="ECA">
                            <label for="ECA">En cours d'acquisition</label>
                        </td>
                        <td>
                            <input type="radio" name="statut" id="NA">
                            <label for="NA">Non acquis</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Matiere:
                        </td>
                        <td>
                            <select name="matiere" id="matiere">
                                <option value="maths">Maths</option>
                                <option value="physique">Physique</option>
                                <option value="info">Informatique</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Professeur:
                        </td>
                        <td>
                            <select name="professeur" id="professeur">
                                <option value="dupont">Mr.Dupont</option>
                                <option value="durand">Mme.Durand</option>
                                <option value="toto">Mr.Toto</option>
                            </select>
                        </td>
                    </tr>
                </form>
            </table>
            <br>
            <input type="submit" id="valider" value="VALIDER">
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
                            <label for="theme">Thème:</label>
                        </td>
                        <td>
                            <input type="text" name="theme" id="theme">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="description">Description:</label>
                        </td>
                        <td>
                            <input type="text" name="description" id="description">
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
    <div id="header">
        <table>
            <tr>
                <img class="logo" src="logo_skills_tracker_noir.png" alt="logo">
            </tr>
            <tr>
                <button class="b1"> <strong>Mon compte</strong></button>
            </tr>
            <tr>
                <button class="b1"> <strong>Mes compétences</strong></button>
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
        <h1> <strong> <span id="titrePage">MES COMPETENCES TRANSVERSES</span> </strong></h1>
        <div id="emplacement_filtres">
            <button class="filtres"><img src="filter.svg" alt="icon"> FILTRES</button>
            <button class="ajouter"><img src="plus-lg.svg" alt="icon"> AJOUTER</button>
        </div>
        <div id="navigation">
            <strong>
                <div id="titreOnglet">
                    <a href="page_mes_competences.php" class="texteOnglet1">Mes compétences</a>
                    <span class="texteOnglet2">Mes compétences transverses</span>
                    <a href="page_toutes_competences.php" class="texteOnglet3">Toutes mes compétences</a>
                </div>
            </strong>
            <br>
            <div class="matiere">
                <div class="matieres">
                    <table>
                        <tr class="ligne">
                            <span class="titreMatieres">MATHEMATIQUES :</span>
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
                            </ul>
                        </tr>
                        <tr>
                            <span class="titreMatieres"> PHYSIQUE-CHIMIE :</span>
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
                            </ul>
                        </tr>
                        <tr class="ligne">
                            <span class="titreMatieres">ELECTRONIQUE :</span>
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
                            </ul>
                            <span class="titreMatieres"> PHYSIQUE-CHIMIE :</span>
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
                            </ul>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>