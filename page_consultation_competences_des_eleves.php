<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="page_consultation_competences_des_eleves.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="icon_onglet.png" href="icon_onglet.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Liste des élèves</title>
</head>

<body>
    <script type="text/javascript" src="page_consultation_competences_des_eleves.js"></script>
    <div id="header">
        <table>
            <tr>
                <img class="logo" src="logo_skills_tracker_noir.png" alt="logo">
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
        <div id="popup_check">
            <span class="fermer"><img src="x-circle.svg" alt="croix"></span>
            <p id="text_check">
                Vous avez validé l'évaluation de l'élève !
            </p>
        </div>
        <div id="popup_x">
            <div class="contenu_popup_x">
                <span class="fermer"><img src="x-circle.svg" alt="croix"></span>
                <table>
                    <form action="">
                        <tr>
                            <td>
                                Modifier l'évaluation:
                            </td>
                            <td>
                            <select name="eval" id="eval">
                                <option value="A">A</option>
                                <option value="ECA">ECA</option>
                                <option value="NA">NA</option>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            <td>
                                Commentaire:
                            </td>
                            <td>
                                <input type="text" id="commentaire">
                            </td>
                        </tr>
                    </form>
                </table>
                <br>
                <input type="submit" id="valider" value="VALIDER">
            </div>
        </div>
        <br>
        <h1> <strong>LISTE DES ELEVES</strong></h1>
        <br>
        <h2><strong>ING 2</strong></h2>
        <br>
        <h4>LES ELEVES : </h4>
        <div class="liste_eleves">
            <ul>
                <li>
                    Emma Smith
                    <button class="scroll" id="scroll_1"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <div id="consul_comp">
                        <ul>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit : A
                                <button class="check" id="bt_check"><img src="check-lg.svg" alt="icon"></button>
                                <button class="x" id="bt_x"><img src="x-lg.svg" alt="icon"></button>
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit : NA
                                <button class="check"><img src="check-lg.svg" alt="icon"></button>
                                <button class="x"><img src="x-lg.svg" alt="icon"></button>
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit : ECA
                                <button class="check"><img src="check-lg.svg" alt="icon"></button>
                                <button class="x"><img src="x-lg.svg" alt="icon"></button>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    Lucas Johnson
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Olivia Brown
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Liam Taylor
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Ava Miller
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Noah Anderson
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                    <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>

                <li>
                    Isabella Martinez
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Sophia Davis
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
                <li>
                    Benjamin Wilson
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>

                <li>
                    Mia Thompson
                    <button class="scroll"><img src="arrow-down-square.svg" alt="icon" class="icon_scroll"></button>
                    <!--                     <button class="check"><img src="check-lg.svg" alt="icon"></button>
                <button class="x"><img src="x-lg.svg" alt="icon"></button> -->
                </li>
            </ul>
        </div>
    </div>
</body>

</html>