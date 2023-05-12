<?php
require_once 'src/database/DatabaseTable.php';

class Evaluation extends DatabaseTable
{
    const TABLE_NAME = 'Evaluations';
    const TABLE_TYPE = Evaluation::class;

    public function __construct($AutoEvaluation,  $idEleve, $idCompetences, $idMatiere, $validation = null, $evaluationFinale = null, $dateEvaluation = null)
    {
        $this->AutoEvaluation = $AutoEvaluation;
        $this->dateAutoEvaluation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->validation = $validation;
        $this->evaluationFinale = $evaluationFinale;
        $this->dateEvaluation = $dateEvaluation;
        $this->idEleve = $idEleve;
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

    #[TableOpt(TableForeignKey: User::class)]
    private int $idEleve;
    #[TableOpt(TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(TableForeignKey: Matiere::class)]
    private int $idMatiere;
}
