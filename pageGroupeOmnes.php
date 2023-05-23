
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
    <link href="res/css/pagegroupeOmnes.css" rel="stylesheet" type="text/css" />
    <link href="res/css/nettoyageStrong.css" rel="stylesheet" type="text/css" />
    <link href="res/css/toolbar.css" rel="stylesheet" type="text/css" />
    <link href="res/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="res/img/icon_onglet.png" href="icon_onglet.png">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Groupe OMNES</title>
</head>

<body>
    <div class="toolbar">
        <a href="/"><img class="logo" src="res/img/logo_skills_tracker_noir.png" alt="logo"></a>
        <a href="/listing.php"><button class="toolbar-btn"><span id="gras"> Mes matières</span></button></a>
        <a><button class="toolbar-btn"> <span id="gras">Mes compétences</span></button></a>
    </div>
    <div id="wrapper">
        <h1>MES INFORMATIONS : GROUPE OMNES </h1>
        <div class="container">
            <div class="col-lg-4">
                <div class="content_col_1">
                    <h3>DERNIERES COMPETENCES</h3>
                    <ul>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : A
                        </li>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : NA
                        </li>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : ECA
                        </li>
                    </ul>
                    <h3>COMPETENCES TENDANCES</h3>
                    <ul>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : A
                        </li>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : NA
                        </li>
                        <li>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit : ECA
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content_col_2">
                    <h3>MA FILIERE :</h3>
                    <ul>
                        <li>
                            Ingénieur
                        </li>
                    </ul>
                    <h3>MON ECOLE : </h3>
                    <img src="res/img/ECE_LOGO.png" alt="img" id="logo_ece">
                </div>
            </div>
        </div>
    </div>
</body>

</html>