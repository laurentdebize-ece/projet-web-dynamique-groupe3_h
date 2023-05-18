<?php

require_once 'src/database/DatabaseTable.php';

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    public function __construct($annee, $idFiliere)
    {
        $this->annee = $annee;
        $this->idFiliere = $idFiliere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idPromo = null;

    private int $annee;
    
    #[TableOpt(TableForeignKey: Filiere::class)]
    private int $idFiliere;
}
