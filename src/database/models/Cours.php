<?php
require_once 'src/database/DatabaseTable.php';

class Cours extends DatabaseTable
{
    const TABLE_NAME = 'Cours';
    const TABLE_TYPE = Cours::class;

    public function __construct($volumeHoraire, $idClasse, $idMatiere)
    {
        $this->volumeHoraire = $volumeHoraire;
        $this->idClasse = $idClasse;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCours = null;

    private float $volumeHoraire;
    private int $idClasse;
    private int $idMatiere;
}
