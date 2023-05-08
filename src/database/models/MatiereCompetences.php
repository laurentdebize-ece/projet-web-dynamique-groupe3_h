<?php
require_once 'src/database/DatabaseTable.php';

class MatiereCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Matiere/Competences';
    const TABLE_TYPE = MatiereCompetences::class;

    public function __construct($idCompetences, $idMatiere)
    {
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiereCompetences = null;

    #[TableOpt(ForeignKey: true, TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(ForeignKey: true, TableForeignKey: Matiere::class)]
    private int $idMatiere;
}
