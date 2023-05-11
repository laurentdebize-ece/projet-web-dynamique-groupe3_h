<?php
require_once 'src/database/DatabaseTable.php';

class Cours extends DatabaseTable
{
    const TABLE_NAME = 'Cours';
    const TABLE_TYPE = Cours::class;

    public function __construct($volumeHoraire, $idClasse, $idMatiere, $idProfesseur)
    {
        $this->volumeHoraire = $volumeHoraire;
        $this->idClasse = $idClasse;
        $this->idMatiere = $idMatiere;
        $this->idProfesseur = $idProfesseur;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCours = null;

    private float $volumeHoraire;

    #[TableOpt(TableForeignKey: Classe::class)]
    private int $idClasse;
    #[TableOpt(ForeignKey: true, TableForeignKey: Matiere::class)]
    private int $idMatiere;
    #[TableOpt(ForeignKey: true, TableForeignKey: User::class)]
    private int $idProfesseur;
}
