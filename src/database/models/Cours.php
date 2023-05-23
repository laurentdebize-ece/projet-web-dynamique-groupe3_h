<?php
require_once 'src/database/DatabaseTable.php';

class Cours extends DatabaseTable
{
    const TABLE_NAME = 'Cours';
    const TABLE_TYPE = Cours::class;

    public function __construct(float $volumeHoraire, int $idClasse, int $idMatiere, int $idProfesseur)
    {
        $this->volumeHoraire = $volumeHoraire;
        $this->idClasse = $idClasse;
        $this->idMatiere = $idMatiere;
        $this->idProfesseur = $idProfesseur;
    }

    /// Retourne toutes les ID de classes pour lesquelles le professeur a des cours.
    public static function getClassesForProfesseur(DatabaseController $db, int $idUser): array
    {
        return Cours::select($db, "DISTINCT `classes`.idClasse, numGroupe", ["JOIN `classes` on `classes`.`idClasse` = `cours`.`idClasse`", "WHERE `idProfesseur` = $idUser"])->fetchAll();
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
