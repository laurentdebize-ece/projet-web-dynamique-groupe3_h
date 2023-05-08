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

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idThemesCompetences = null;

    #[TableOpt(ForeignKey: true, TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(ForeignKey: true, TableForeignKey: Theme::class)]
    private int $idTheme;
}
