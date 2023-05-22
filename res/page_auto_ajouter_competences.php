<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="page_auto_ajouter_competences.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="icon_onglet.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Ajouter compétences</title>
</head>

<body>
    <script type="text/javascript" src="page_auto_ajouter_competences.js"></script>
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
                <button class="b1"> <strong>Ajouter compétences</strong></button>
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
</body>

</html>