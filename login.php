<?php
header("no-cache");
require_once "src/database/Database.php";
require_once "src/database/models/User.php";


/// Vérification de l'authentification

$pass_incorrect = false;
$email_incorrect = false;

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $db = DatabaseController::getInstance();
    $user = User::authenticate($db, $_POST["email"], $_POST["password"]);

    $pass_incorrect = $user === false;
    $email_incorrect = $user === null;

    if (is_object($user)) {
        if (isset($_POST["redirect"])) {
            header("Location: " . $_POST["redirect"]);
        } else {
            header("Location: /");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/login.css" />
    <title>Connexion</title>
</head>

<body>
    <div class="panel">
        <div class="panel-header">
            <img src="res/img/login.jpg" alt="logo" />
        </div>
        <div class="form">
            <form method="post">
                <h1>Connexion</h1>
                <fieldset>
                    <legend>addresse mail</legend>
                    <input type="text" name="email" id="email" placeholder="nom.prenom@edu.ece.fr" required />
                </fieldset>
                <fieldset>
                    <legend>mot de passe</legend>
                    <input type="password" name="password" id="password" placeholder="****" required />
                </fieldset>
                <input type="submit" value="connexion" id="login" />
                <?php
                if (isset($_GET["redirect"])) {
                    echo "<input type='hidden' name='redirect' value='" . $_GET["redirect"] . "' />";
                }
                ?>
            </form>
            <?php
            if ($email_incorrect) {
                echo "<p class='incorrect'>L'adresse mail n'est pas enregistrée.</p>";
            } else if ($pass_incorrect) {
                echo "<p class='incorrect'>Le mot de passe est incorrect.</p>";
            }
            ?>
            <a class="underlined" href="resetpasswd">Vous avez oublié votre mot de passe ?</a>
        </div>
    </div>
</body>

</html>