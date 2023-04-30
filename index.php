<?php
$password = 'lukeh';
$hash_password = password_hash($password,PASSWORD_BCRYPT);

if(isset($_POST['mdp']) and !empty($_POST['mdp'])){
    $login = password_verify($_POST['mdp'],$hash_password);
    if ($login === True){
        echo "Mot de passe correct";
    }
    else{
        echo "Mot de passe incorrect";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test MDP</title>
</head>
<body>
    <div>
        <h2>Verification Mot de passe</h2>
        <form action="index.php" method="post">
            <label for="mdp">Mot de passe</label><input type="text" placeholder="Entrez un mot de passe" id="mdp" name="mdp">
            <input type="submit" value="Envoyer">
        </form>
    </div>
</body>
</html>
