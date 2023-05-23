<?php
require_once 'src/database/DatabaseTable.php';

class Filiere extends DatabaseTable
{
    const TABLE_NAME = 'Filieres';
    const TABLE_TYPE = Filiere::class;

    public function __construct(string $nomFiliere, int $idEcole)
    {
        $this->nomFiliere = $nomFiliere;
        $this->idEcole = $idEcole;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idFiliere = null;

    private string $nomFiliere;

    #[TableOpt(TableForeignKey: Ecole::class)]
    private int $idEcole;

    // retourne toutes les filières contenant l'école
    public static function getAllFilieres(DatabaseController $db): ?array
    {
        $allfilieres = array();
        $filieres = Filiere::select($db,null,["ORDER BY idFiliere ASC"])->fetchAll();
        foreach ($filieres as $filiere)
        {
            $idEcole = intval($filiere['idEcole']);
            $ecole = Ecole::select($db,null,["WHERE","idEcole = $idEcole","LIMIT 1"])->fetch();

            $filiereInfo = [$filiere['nomFiliere'],$ecole['nomEcole']];
            $idFiliere = intval($filiere['idFiliere']);
            $allfilieres[$idFiliere] = $filiereInfo;
        }
        return $allfilieres;
    }

    // retourne toutes les filières d'une école
    public static function getAllFilieresBySchool(DatabaseController $db, int $idEcole): ?array
    {
        $allfilieres = array();
        $filieres = Filiere::select($db,null,["WHERE","idEcole = $idEcole"])->fetchAll();
        foreach ($filieres as $filiere)
        {
            $nomFiliere = $filiere['nomFiliere'];
            $idFiliere = intval($filiere['idFiliere']);
            $allfilieres[$idFiliere] = $nomFiliere;
        }
        return $allfilieres;
    }

    // créer une filière depuis un admin
    public static function createFiliere(DatabaseController $db, int $idUser, string $nomFiliere, int $idEcole): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            $ecole = Ecole::select($db, null, ["WHERE", "`idEcole` = $idEcole", "LIMIT 1"])->fetchTyped();
            if($ecole === null) {
                throw new Exception("L'école `" . $idEcole . "` n'existe pas");
            }
            else {
                $nomFiliereSafe = htmlspecialchars($nomFiliere);
                if ($nomFiliereSafe !== $nomFiliere){
                    throw new InvalidArgumentException("Arrêtez d'essayer de hacker le site");
                }
                $nomFiliereSQL = classQL::escapeSQL($nomFiliereSafe);
                $filiere = Filiere::select($db, null, ["WHERE", "`nomFiliere` = '$nomFiliereSQL' AND `idEcole` = '$idEcole' ", "LIMIT 1"])->fetchTyped();
                if($filiere !== null) {
                    throw new Exception("Cette filière existe déjà");
                }
                else{
                    $filiere = new Filiere($nomFiliere, $idEcole);
                    Filiere::insert($db, $filiere);
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut créer une filière");
        }
    }

    // modifier une filière depuis un admin
    public static function modifyFiliere(DatabaseController $db, int $idUser, int $idFiliere, string $nomFiliere, int $idEcole): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            $filiere = Filiere::select($db, null, ["WHERE", "`idFiliere` = $idFiliere", "LIMIT 1"])->fetch();
            if($filiere === false) {
                throw new Exception("La filière `" . $idFiliere . "` n'existe pas");
            }
            else {
                $ecole = Ecole::select($db, null, ["WHERE", "`idEcole` = $idEcole", "LIMIT 1"])->fetchTyped();
                if($ecole === null) {
                    throw new Exception("L'école `" . $idEcole . "` n'existe pas");
                }
                else {
                    $nomFiliereSafe = htmlspecialchars($nomFiliere);
                    $idEcoleSafe = intval($idEcole);
                    if ($nomFiliereSafe !== $nomFiliere or $idEcoleSafe !== $idEcole){
                        throw new InvalidArgumentException("Arrêtez d'essayer de hacker le site");
                    }
                    $nomFiliereSQL = classQL::escapeSQL($nomFiliereSafe);

                    $filiere['nomFiliere'] = $nomFiliereSafe;
                    $filiere['idEcole'] = $idEcoleSafe;

                    $filiereInsert = (is_null(Filiere::select($db, null, ["WHERE", "`nomFiliere` = '$nomFiliereSQL'", "AND" ,"`idEcole` = '$idEcoleSafe'" ,"LIMIT 1"])->fetchTyped()))
                    ? classQL::createFromFields($filiere, Filiere::class) : null;
                    if ($filiereInsert !== null) {
                        Ecole::modify($db, $filiereInsert);
                    }
                    else {
                        throw new Exception("Cette filiere existe déjà vous ne pouvez pas en créer une autre");
                    }
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut modifier une filière");
        }
    }

    // supprimer une filière depuis un admin
    public static function deleteFiliere(DatabaseController $db, int $idUser, array $idFilieres): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            if (!empty($idFilieres)){
                foreach ($idFilieres as $idFiliere){
                    $idFiliere = intval($idFiliere);
                    $filiere = Filiere::select($db, null, ["WHERE", "`idFiliere` = $idFiliere", "LIMIT 1"])->fetchTyped();
                    if($filiere === null) {
                        throw new Exception("La filière `" . $idFiliere . "` n'existe pas");
                    }
                    else {
                        Filiere::delete($db, $filiere);
                    }
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut supprimer une filière");
        }
    }
}