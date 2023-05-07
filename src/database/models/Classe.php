<?php
require_once 'src/database/DatabaseTable.php';

class Classe extends DatabaseTable
{
    const TABLE_NAME = 'Classes';
    const TABLE_TYPE = Classe::class;

    public function __construct($numGroupe, $idPromo, $idEleve)
    {
        $this->numGroupe = $numGroupe;
        $this->idPromo = $idPromo;
        $this->idEleve = $idEleve;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idClasse = null;

    private int $numGroupe;
    private int $idPromo;
    private int $idEleve;
}
