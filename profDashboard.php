<?php
header("no-cache, no-store, must-revalidate");
require_once "src/session.php";
$sess = SessionManager::getInstance();
$sess->ensureHasAuthority(User::ACCOUNT_TYPE_PROF);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/toolbar.css" type="text/css">
    <link rel="stylesheet" href="res/css/dashboard.css" type="text/css">
    <link rel="icon" type="icon" href="res/img/icon_onglet.png">
    <title>Accueil</title>
</head>

<body>
<div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/classes.php"><button class="toolbar-btn"> <strong>Mes classes</strong></button></a>
        <div class="toolbar-logout">
            <a href="/logout.php"><button class="toolbar-btn"> <strong>Se déconnecter</strong></button></a>
        </div>
    </div>
    <div id="wrapper">
        <div id="content">
            <div id="navigation">
                <p>
                <h1> <span id="titre">Ravi de vous revoir, <?php echo $sess->getUser()->getDisplayName() ?></span> </h1> <br>
                <h2>
                    Bienvenue sur le dashboard prof de Skills Tracker. Modifiez et gérez les évaluations de vos élèves.
                </h2>
                </p>
            </div>
        </div>
    </div>
    <footer>
        Copyright © 2022 Skills Tracker <br>
        <span><img src="telephone-outbound.svg" alt="icon"> (+33) 6 34 56 78 43</span>
        <br>
        <a href="mailto:contact@skillstracker.com" class="email">
            <img src="envelope-at.svg" alt="icon">
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

</body>

</html>