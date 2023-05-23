<?php
require_once 'src/database/DatabaseTable.php';

class Ecole extends DatabaseTable
{
    const TABLE_NAME = 'Ecoles';
    const TABLE_TYPE = Ecole::class;

    public function __construct(string $nomEcole, string $typeEtude)
    {
        $this->nomEcole = $nomEcole;
        $this->typeEtude = $typeEtude;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEcole = null;

    #[TableOpt(Unique: true)]
    private string $nomEcole;
    private string $typeEtude;


    // retourne toutes les écoles
    public static function getAllSchools(DatabaseController $db): ?array
    {
        $schools = array();
        $ecoles = Ecole::select($db,null,["ORDER BY idEcole ASC"])->fetchAll();
        foreach ($ecoles as $ecole)
        {
            $nomEcole = $ecole['nomEcole'];
            $idEcole = intval($ecole['idEcole']);
            $schools[$idEcole] = $nomEcole;
        }
        return $schools;
    }

    // créer une école depuis un admin
    public static function createSchool(DatabaseController $db, int $idUser, string $nomEcole, string $typeEtude): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            $nomEcoleSafe = htmlspecialchars($nomEcole);
            $typeEtudeSafe = htmlspecialchars($typeEtude);
            if ($nomEcoleSafe !== $nomEcole || $typeEtudeSafe !== $typeEtude){
                throw new InvalidArgumentException("Arrêtez d'essayer de hacker le site");
            }
            $ecole = Ecole::select($db, null, ["WHERE", "`nomEcole` = '$nomEcole'", "LIMIT 1"])->fetchTyped();
            if($ecole !== null) {
                throw new Exception("Cette école existe déjà");
            }
            else{
                $ecole = new Ecole($nomEcole, $typeEtude);
                Ecole::insert($db, $ecole);
            }
        }
        else {
            throw new Exception("Seul un administrateur peut créer une école");
        }
    }

    // modifie une école depuis un admin
    public static function modifySchool(DatabaseController $db, int $idUser, int $idEcole, string $nomEcole , string $typeEtude): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() !== User::ACCOUNT_TYPE_ADMIN)
        {
            throw new Exception("Seul un administrateur peut modifier une école");
        }
        else {
            $ecole = Ecole::select($db, null, ["WHERE", "`idEcole` = '$idEcole'", "LIMIT 1"])->fetch();
            if($ecole === false) {
                throw new Exception("Cette école n'existe pas");
            }
            else {
                $nomEcoleSafe = htmlspecialchars($nomEcole);
                $typeEtudeSafe = htmlspecialchars($typeEtude);

                if ($nomEcoleSafe !== $nomEcole or $typeEtudeSafe !== $typeEtude){
                    throw new InvalidArgumentException("Arrêtez d'essayer de hacker le site");
                }

                $ecole['nomEcole'] = $nomEcoleSafe;
                $ecole['typeEtude'] = $typeEtudeSafe;
                
                $nomEcoleSQL = classQL::escapeSQL($nomEcoleSafe);
                $typeEtudeSQL = classQL::escapeSQL($typeEtudeSafe);

                if (Ecole::select($db, null, ["WHERE", "`nomEcole` = '$nomEcoleSQL'","LIMIT 1"])->fetch() !== false) {
                    throw new Exception("Vous ne pouvez pas emprunter le nom d'une autre école");
                }

                $ecoleInsert = (is_null(Ecole::select($db, null, ["WHERE", "`nomEcole` = '$nomEcoleSQL'", "AND" ,"`typeEtude` = '$typeEtudeSQL'" ,"LIMIT 1"])->fetchTyped()))
                ? classQL::createFromFields($ecole, Ecole::class) : null;
                if ($ecoleInsert !== null) {
                    Ecole::modify($db, $ecoleInsert);
                }
                else {
                    throw new Exception("Cette école existe déjà vous ne pouvez pas en creer une autre");
                }
            }
        }
    }

    // supprime une école depuis un admin
    public static function deleteSchool(DatabaseController $db, int $idUser, array $idEcoles): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() !== User::ACCOUNT_TYPE_ADMIN)
        {
            throw new Exception("Seul un administrateur peut supprimer une école");
        }
        else {
            if(!empty($idEcoles)){
                foreach ($idEcoles as $idEcole) {
                    $idEcole = intval($idEcole);
                    $ecole = Ecole::select($db, null, ["WHERE", "`idEcole` = '$idEcole'", "LIMIT 1"])->fetchTyped();
                    if($ecole === null) {
                        throw new Exception("L'école" . $idEcole . "n'existe pas");
                    }
                    else {
                        Ecole::delete($db, $ecole);
                    }
                }
            }
        }
    }
}
