<?php
require_once 'src/database/DatabaseTable.php';

class Matiere extends DatabaseTable
{
    const TABLE_NAME = 'Matieres';
    const TABLE_TYPE = Matiere::class;

    public function __construct($nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiere = null;

    private string $nomMatiere;
}
