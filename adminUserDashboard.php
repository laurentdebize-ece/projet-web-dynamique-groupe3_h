<?php
//fixme: reorganiser mais ca restera très surement un bordel car crunch time.
header("no-cache, no-store, must-revalidate");
require_once 'src/session.php';

$session = SessionManager::getInstance();
$session->ensureHasAuthority(User::ACCOUNT_TYPE_ADMIN);

function getAllClasses()
{
    return Classe::select(DatabaseController::getInstance(), null, [
        "LEFT JOIN `promotions` ON `classes`.`idPromo` = `promotions`.`idPromo`",
        "LEFT JOIN `filieres` ON `filieres`.`idFiliere` = `promotions`.`idFiliere`",
        "LEFT JOIN `ecoles` ON `ecoles`.`idEcole` = `filieres`.`idEcole`"
    ])->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/adminUserDashboard.css">
    <script src="res/js/jquery-3.7.0.min.js"></script>
    <script src="res/js/bootstrap.bundle.min.js"></script>
    <script src="res/js/adminUserDashboard.js"></script>
    <title>Admin - Gestion des utilisateurs</title>
</head>

<?php
/// Gestion des requêtes
if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "create_user":
            if (isset($_POST["type"])) {
                $accType = User::ACCOUNT_TYPE_USER;
                switch ($_POST["type"]) {
                    case "admin":
                        $accType = User::ACCOUNT_TYPE_ADMIN;
                        break;

                    case "prof":
                        $accType = User::ACCOUNT_TYPE_PROF;
                        break;

                    case "user":
                        $accType = User::ACCOUNT_TYPE_USER;
                        break;
                }
                if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["email"]) && isset($_POST["password"])) {
                    $nom = $_POST["nom"];
                    $prenom = $_POST["prenom"];
                    $email = $_POST["email"];
                    $mdp = $_POST["password"];
                    $idClasse = isset($_POST["classe"]) ? $_POST["classe"] : null;
                    $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);

                    if ($accType == User::ACCOUNT_TYPE_USER) {
                        if ($idClasse == null) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Une erreur est survenue lors de la création de l'utilisateur : Classe invalide
                          </div>";
                            break;
                        }
                        $user = new User($accType, $email, $nom, $prenom, $hashMdp, $idClasse);
                        try {
                            User::insert(DatabaseController::getInstance(), $user);
                        } catch (Exception $e) {
                            $errMsg = $e->getMessage();
                            echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Une erreur est survenue lors de la création de l'utilisateur : $errMsg
                          </div>";
                        }
                    } else {
                        $user = new User($accType, $email, $nom, $prenom, $hashMdp);
                        try {
                            User::insert(DatabaseController::getInstance(), $user);
                        } catch (Exception $e) {
                            $errMsg = $e->getMessage();
                            echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Une erreur est survenue lors de la création de l'utilisateur : $errMsg
                          </div>";
                        }
                    }
                }
            }
            break;

        case "del_user":
            if (isset($_POST["id"])) {
                $id = $_POST["id"];
                try {
                    $user = User::select(DatabaseController::getInstance(), null, ["WHERE `idUser` = $id", "LIMIT 1"])->fetchAllTyped();
                    if (count($user) > 0) {
                        User::delete(DatabaseController::getInstance(), $user[0]);
                    }
                } catch (Exception $e) {
                    $errMsg = $e->getMessage();
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                    Une erreur est survenue lors de la suppression de l'utilisateur : $errMsg
                  </div>
                  ";
                }
            }
            break;
    }
}
?>


<body>
    <button class="btn btn-primary" id="adduserbtn"> ➕ Créer un utilisateur</button>
    <div class="btn-group" role="group" aria-label="filtre">
        <a href="adminUserDashboard.php?filter=all" class="btn btn-primary">Tout</a>
        <a href="adminUserDashboard.php?filter=prof" class="btn btn-primary">Professeurs</a>
        <a href="adminUserDashboard.php?filter=eleve" class="btn btn-primary">Eleves</a>
        <a href="adminUserDashboard.php?filter=admin" class="btn btn-primary">Administrateurs</a>
    </div>
    <table class="table table-light table-stripped table-hover">
        <thead>
            <tr>
                <th>Prénom / Nom</th>
                <th>Type</th>
                <th>Classe</th>
                <th>e-mail</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $reqFilter = [];

            if (isset($_GET["filter"])) {
                switch ($_GET["filter"]) {
                    case "all":
                        $reqFilter = [];
                        break;

                    case "prof":
                        $reqFilter = ["WHERE `typeAccount` = 2"];
                        break;

                    case "eleve":
                        $reqFilter = ["WHERE `typeAccount` = 1"];
                        break;

                    case "admin":
                        $reqFilter = ["WHERE `typeAccount` = 0"];
                        break;

                    default:
                        $reqFilter = [];
                        break;
                }
            }

            $users = User::select(DatabaseController::getInstance(), null, array_merge([
                "LEFT JOIN `classes` ON `users`.`idClasse` = `classes`.`idClasse`",
                "LEFT JOIN `promotions` ON `classes`.`idPromo` = `promotions`.`idPromo`",
                "LEFT JOIN `filieres` ON `filieres`.`idFiliere` = `promotions`.`idFiliere`",
                "LEFT JOIN `ecoles` ON `ecoles`.`idEcole` = `filieres`.`idEcole`"
            ], $reqFilter))->fetchAll();

            foreach ($users as $user) {
                $uid = $user["idUser"];

                echo "<tr>";
                echo "<td>" . $user["nomUser"] . " " . $user["prenomUser"] . "</td>";
                $typesComptes = ["Admin", "Eleve", "Prof"];
                echo "<td>" . $typesComptes[$user["typeAccount"]] . "</td>";
                if ($user["numGroupe"] == null) {
                    echo "<td> </td>";
                } else {
                    echo "<td> <span class=\"rounded-pill text-bg-info\">" . $user['numGroupe'] . " / "  . $user['nomFiliere'] . " " . $user['nomEcole'] . " / " . $user['annee'] . "</span> </td>";
                }
                echo "<td>" . $user["email"] . "</td>";
                echo "<td>" . "<button onclick=\"showUserEditPopup($uid)\" class=\"btn text-bg-danger\"> 💀 </button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

    <!-- Fenêtre modale de modification d'utilisateurs -->
    <div class="modal modal-l fade" tabindex="-1" id="editusermodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Voulez vous vraiment supprimer cet utilisateur ? (Il part en vacances à tout jamais dans la corbeille)
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <form method="post">
                        <input type="hidden" name="action" value="del_user">
                        <input type="hidden" name="type" value="admin">
                        <input type="hidden" name="id" id="edited_student_id" value="1">
                        <input type="submit" value="Supprimer" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Fenêtre modale de création d'utilisateurs -->
    <div class="modal modal-xl fade" id="addusermodal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un nouvel utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <p> Type d'utilisateur : </p>
                        <div class="btn-group">
                            <button class="btn btn-primary" id="createadminbtn"> Admin 🏗</button>
                            <button class="btn btn-primary" id="createprofbtn"> Professeur 👴</button>
                            <button class="btn btn-primary" id="createelevebtn"> Eleve 👨‍🎓</button>
                        </div>

                        <!-- Formulaire de création d'admin -->
                        <div class="collapse" id="createadmin">
                            <div class="card card-body">
                                <form method="post">
                                    <input type="hidden" name="action" value="create_user">
                                    <input type="hidden" name="type" value="admin">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">e-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <p>IMPORTANT: En créeant un compte admin vous donnez la possibilité à un autre utilisateur de manipuler le site.</p>
                                    <input type=submit value="Créer" class="btn btn-primary">
                                </form>
                            </div>
                        </div>

                        <div class="collapse" id="createeleve">
                            <div class="card card-body">
                                <form method="post">
                                    <input type="hidden" name="action" value="create_user">
                                    <input type="hidden" name="type" value="user">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">e-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="classe" class="form-label">Classe</label>
                                        <select class="form-select" name="classe">
                                            <?php
                                            foreach (getAllClasses() as $classe) {
                                                echo "<option value=\"" . $classe["idClasse"] . "\">" . $classe["numGroupe"] . " " . $classe["nomEcole"] . " " . $classe["nomFiliere"] . " " . $classe["annee"] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <input type=submit value="Créer" class="btn btn-primary">
                                </form>
                            </div>
                        </div>

                        <div class="collapse" id="createprof">
                            <div class="card card-body">
                                <form method="post">
                                    <input type="hidden" name="action" value="create_user">
                                    <input type="hidden" name="type" value="prof">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">e-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <input type=submit value="Créer" class="btn btn-primary">
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
</body>

</html>