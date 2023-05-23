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

    #[TableOpt(Unique: true)]
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
}