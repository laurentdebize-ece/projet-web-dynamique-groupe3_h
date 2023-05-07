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

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idCompetences;

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idMatiere;
}
