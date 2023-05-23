<?php
require_once 'src/database/DatabaseTable.php';

class Classe extends DatabaseTable
{
    const TABLE_NAME = 'Classes';
    const TABLE_TYPE = Classe::class;

    public function __construct(int $numGroupe, int $idPromo, int $effectif = null)
    {
        $this->numGroupe = $numGroupe;
        $this->effectif = $effectif;
        $this->idPromo = $idPromo;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idClasse = null;

    private int $numGroupe;
    private ?int $effectif;

    #[TableOpt(TableForeignKey: Promotion::class)]
    private int $idPromo;

    // retourne un tableau de toutes les classes contenant la promotion, la filière et l'école
    public static function getAllClasses(DatabaseController $db): ?array
    {
        $classes = array();
        $classesArray = Classe::select($db,null,["ORDER BY idClasse ASC"])->fetchAll();
        foreach ($classesArray as $classe)
        {
            $numGroupe = $classe['numGroupe'];
            $effectif = $classe['effectif'];

            $idPromo = intval($classe['idPromo']);
            $promo = Promotion::select($db,null,["WHERE","idPromo = $idPromo","LIMIT 1"])->fetch();
            $nomPromo = $promo['annee'];

            $idFiliere = intval($promo['idFiliere']);
            $filiere = Filiere::select($db,null,["WHERE","idFiliere = $idFiliere","LIMIT 1"])->fetch();
            $nomFiliere = $filiere['nomFiliere'];

            $idEcole = intval($filiere['idEcole']);
            $ecole = Ecole::select($db,null,["WHERE","idEcole = $idEcole","LIMIT 1"])->fetch();
            $nomEcole = $ecole['nomEcole'];

            $classeInfo = [$numGroupe,$effectif,$nomPromo,$nomFiliere,$nomEcole];
            $idClasse = intval($classe['idClasse']);
            $classes[$idClasse] = $classeInfo;
        }
        return $classes;
    }

    // retourne un tableau de toutes les classes d'une promotion
    public static function getAllClasseByPromo(DatabaseController $db, int $idPromo): ?array
    {
        $classes = array();
        $classesArray = Classe::select($db, null, ["WHERE", "idPromo = $idPromo"])->fetchAll();
        foreach ($classesArray as $classe) {
            $numGroupe = $classe['numGroupe'];
            $idClasse = intval($classe['idClasse']);
            $classes[$idClasse] = $numGroupe;
        }
        return $classes;
    }

    // retourne un tableau de toutes les étudiants d'une classe
    public static function getAllStudentsByClasse(DatabaseController $db, int $idClasse): ?array
    {
        $table_user = User::TABLE_NAME;
        $students = array();
        $studentsArray = User::select($db, null, ["WHERE", "$table_user.idClasse = $idClasse"])->fetchAll();
        foreach ($studentsArray as $student) {
            $nom = $student['nomUser'];
            $prenom = $student['prenomUser'];
            $idUser = intval($student['idUser']);
            $students[$idUser] = $nom . " " . $prenom;
        }
        return $students;
    }

    // créer une classe depuis un admin
    public static function createClasse(DatabaseController $db, int $idUser, int $numGroupe, int $idPromo, ?int $effectif = null): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            $numGroupe = intval($numGroupe);
            $idPromo = intval($idPromo);
            $promo = Promotion::select($db, null, ["WHERE", "`idPromo` = $idPromo", "LIMIT 1"])->fetchTyped();
            if($promo === null) {
                throw new Exception("Cette promotion n'existe pas");
            }
            else {
                $classe = Classe::select($db, null, ["WHERE", "`numGroupe` = $numGroupe", "AND", "`idPromo` = $idPromo", "LIMIT 1"])->fetchTyped();
                if($classe !== null) {
                    throw new Exception("Cette classe existe déjà");
                }
                else {
                    $classe = ($effectif !== null)? new Classe($numGroupe, $idPromo, $effectif) : new Classe($numGroupe, $idPromo);
                    Classe::insert($db, $classe);
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut créer une classe");
        }
    }

    // modifier une classe depuis un admin
    public static function modifyClasse(DatabaseController $db, int $idUser, int $idClasse, int $numGroupe, int $idPromo, ?int $effectif = null): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            $numGroupe = intval($numGroupe);
            $idPromo = intval($idPromo);
            
            $promo = Promotion::select($db, null, ["WHERE", "`idPromo` = $idPromo", "LIMIT 1"])->fetchTyped();
            if($promo === null) {
                throw new Exception("La promotion" . $idPromo . " n'existe pas");
            }
            else {
                $classe = Classe::select($db, null, ["WHERE", "`numGroupe` = $numGroupe", "AND", "`idPromo` = $idPromo", "LIMIT 1"])->fetchTyped();
                if($classe !== null and $effectif === null) {
                    throw new Exception("Cette classe existe déjà");
                }
                else {
                    $classe = Classe::select($db, null, ["WHERE", "`idClasse` = $idClasse", "LIMIT 1"])->fetch();
                    if($classe === false) {
                        throw new Exception("Cette classe n'existe pas");
                    }
                    else {
                        $classe['numGroupe'] = $numGroupe;
                        $classe['idPromo'] = $idPromo;

                        if ($effectif !== null) {
                            $classe['effectif'] = $effectif;
                        }
                        
                        $classeInsert = (is_null(Classe::select($db, null, ["WHERE", "`numGroupe` = '$numGroupe'", "AND" ,"`idPromo` = '$idPromo'" ,"LIMIT 1"])->fetchTyped()) or $effectif !== null)
                        ? classQL::createFromFields($classe, Classe::class) : null;
                        if ($classeInsert !== null) {
                            var_dump($classeInsert);
                            Promotion::modify($db, $classeInsert);
                        }
                        else {
                            throw new Exception("Cette classe existe déjà vous ne pouvez pas en créer une autre");
                        }
                    }
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut modifier une classe");
        }
    }

    // supprimer une classe depuis un admin
    public static function deleteClasse(DatabaseController $db, int $idUser, array $idClasses): void
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if($user->getAccountType() === User::ACCOUNT_TYPE_ADMIN)
        {
            foreach ($idClasses as $idClasse) {
                $idClasse = intval($idClasse);
                $classe = Classe::select($db, null, ["WHERE", "`idClasse` = $idClasse", "LIMIT 1"])->fetchTyped();
                if($classe === null) {
                    throw new Exception("La classe " . $idClasse . " n'existe pas");
                }
                else {
                    Classe::delete($db, $classe);
                }
            }
        }
        else {
            throw new Exception("Seul un administrateur peut supprimer une classe");
        }
    }
}
