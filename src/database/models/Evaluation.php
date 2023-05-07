<?php
require_once 'src/database/DatabaseTable.php';

class Evaluation extends DatabaseTable
{
    const TABLE_NAME = 'Evaluations';
    const TABLE_TYPE = Evaluation::class;

    public function __construct($AutoEvaluation,  $idUser, $idCompetences, $idMatiere, $validation = null, $evaluationFinale = null, $dateEvaluation = null)
    {
        $this->AutoEvaluation = $AutoEvaluation;
        $this->dateAutoEvaluation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->validation = $validation;
        $this->evaluationFinale = $evaluationFinale;
        $this->dateEvaluation = $dateEvaluation;
        $this->idUser = $idUser;
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEvaluation = null;

    private string $AutoEvaluation;
    private DateTime $dateAutoEvaluation;
    private ?bool $validation;
    private ?string $evaluationFinale;
    private ?DateTime $dateEvaluation;
    private int $idUser;
    private int $idCompetences;
    private int $idMatiere;
}
