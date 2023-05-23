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
}
