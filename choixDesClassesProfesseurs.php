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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/bootstrap.min.css" />
    <link rel="stylesheet" href="res/css/toolbar.css" />
<script src="res/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="res/css/choixDesClassesProfesseurs.css" type="text/css">
    <link rel="stylesheet" href="res/js/choixDesClassesProfesseurs.js" type="text/js">
    <link rel="icon" type="icon_onglet.png" href="icon_onglet.png">
    <title>Document</title>
</head>

<body class="background">
<div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
    </div>
     <h1 id="texte2">
        <strong>
            MES CLASSES :
        </strong>
    </h1> 

<div class="ensemble-fleche">
    
    <div class="slide">
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">   CLASSE:<br> <p> 6 ème</p>
                        </div>
                    </td>
                    <td>
                        <div id="espace">      
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte"> CLASSE:<br> <p> 5 ème</p>
                        </div>
                    </td>
                    <td>
                        <div id="espace">    
                        </div>
                    </td>
                    <td>
                        <div id="bouton">
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>

                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">CLASSE:<br><p> 4 ème</p>
                        </div>
                    </td>
                    <td>
                        <div id="espace">   
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">CLASSE:<br><p> 3 ème</p></div>
                    </td>
                    <td>
                        <div id="espace">
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">CLASSE:<br><p> 2 nd</p></div>
                    </td>
                    <td>
                        <div id="espace">
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">CLASSE:<br><p> 1 ère</p></div>
                    </td>
                    <td>
                        <div id="espace">
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        <div id="classe">
            <table>
                <tr>
                    <td>
                        <img src="res/img/imageClasseProfesseur.png" alt="Cinque Terre" width="156" height="120">
                    </td>
                    <td>
                        <div id="texte">CLASSE:<br><p> Terminale</p></div>
                    </td>
                    <td>
                        <div id="espace">
                        </div>
                    </td>
                    
                    <td>
                        <div id="bouton" >
                            <div id="texte2">
                                Accèder
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div> 
        
    </div>
    
</div>  

    
</body>
</html>