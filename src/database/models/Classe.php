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
}
