<?php

require_once 'src/database/DatabaseTable.php';

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    public function __construct($annee, $statut, $idEcole)
    {
        $this->annee = $annee;
        $this->statut = $statut;
        $this->idEcole = $idEcole;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idPromo = null;

    private int $annee;
    private string $statut;
    private int $idEcole;
}
