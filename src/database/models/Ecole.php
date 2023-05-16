<?php
require_once 'src/database/DatabaseTable.php';

class Ecole extends DatabaseTable
{
    const TABLE_NAME = 'Ecoles';
    const TABLE_TYPE = Ecole::class;

    public function __construct($nomEcole, $typeEtude)
    {
        $this->nomEcole = $nomEcole;
        $this->typeEtude = $typeEtude;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEcole = null;

    #[TableOpt(Unique: true)]
    private string $nomEcole;
    private string $typeEtude;
}
