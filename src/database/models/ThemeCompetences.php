<?php
require_once 'src/database/DatabaseTable.php';

class ThemesCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Themes/Competences';
    const TABLE_TYPE = ThemesCompetences::class;

    public function __construct($idCompetences, $idTheme)
    {
        $this->idCompetences = $idCompetences;
        $this->idTheme = $idTheme;
    }

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idCompetences;

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idTheme;
}
