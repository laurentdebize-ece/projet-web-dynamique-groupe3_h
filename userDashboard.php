<?php
header("no-cache, no-store, must-revalidate");
require_once 'src/session.php';
$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_USER);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="res/css/bootstrap.min.css" rel="stylesheet">
    <link href="res/css/toolbar.css" rel="stylesheet" type="text/css">
    <link href="res/css/dashboard.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="icon_onglet.png" href="res/img/icon_onglet.png">
    <title>SkillTracker - Dashboard étudiant</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"> <strong>Mes matières</strong></button></a>
        <a href="/competences.php"><button class="toolbar-btn"> <strong>Mes compétences</strong></button></a>
        <a href="/pageGroupeOmnes.php"><button class="toolbar-btn"> <strong>My Omnes</strong></button></a>
        <div class="toolbar-logout">
            <a href="/logout.php"><button class="toolbar-btn"> <strong>Se déconnecter</strong></button></a>
        </div>
    </div>
    <div id="wrapper">
        <div id="content">
            <div id="navigation">
                <p>
                <h1> <strong> <span id="titre">SKILLS TRACKER</span> </strong></h1> <br>
                <h2>
                    Bienvenue sur Skills Tracker. Consultez toutes vos compétences <br>
                    dans vos différentes matières et auto-évaluez vous dessus <br>
                    en direct.
                </h2>
                </p>
                <br>
                <a href="/listing.php"><button class="b2"><strong>MATIERES</strong></button></a>
            </div>
        </div>
        <footer>
            Copyright © 2022 Skills Tracker <br>
            <span><img src="res/img/telephone-outbound.svg" alt="icon"> (+33) 6 34 56 78 43</span>
            <br>
            <a href="mailto:contact@skillstracker.com" class="email">
                <img src="res/img/envelope-at.svg" alt="icon">
                <span>contact@skillstracker.com</span>
            </a>
            <p>
                <em>
                    Web développeurs :
                    <br>
                    <a href="https://www.instagram.com/raph._.drnd/" target="_blank" class="insta">Raphaël DURAND</a>
                    <br>
                    <a href="https://www.instagram.com/lukeharrrr/" target="_blank" class="insta">Lucas ARRIESSE</a>
                    <br>
                    <a href="https://www.instagram.com/thomaas.bls/" target="_blank" class="insta">Thomas BALSALOBRE
                    </a>
                    <br>
                    <a href="https://www.instagram.com/?next=%2F" target="_blank" class="insta">Jules FEDIT</a>
                </em>
            </p>
        </footer>
    </div>
</body>

</html>