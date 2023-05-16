<?php
require_once 'src/database/DatabaseTable.php';

class Filiere extends DatabaseTable
{
    const TABLE_NAME = 'Filieres';
    const TABLE_TYPE = Filiere::class;

    public function __construct($nomFiliere, $idEcole)
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
}