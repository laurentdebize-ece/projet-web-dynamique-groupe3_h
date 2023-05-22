<?php
require_once 'src/database/DatabaseTable.php';

class Classe extends DatabaseTable
{
    const TABLE_NAME = 'Classes';
    const TABLE_TYPE = Classe::class;

    public function __construct(int $numGroupe, int $idPromo, int $effectif = null)
    {
        $this->numGroupe = $numGroupe;
        $this->effectif = $effectif;
        $this->idPromo = $idPromo;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idClasse = null;

    private int $numGroupe;
    private ?int $effectif;

    #[TableOpt(TableForeignKey: Promotion::class)]
    private int $idPromo;
}
