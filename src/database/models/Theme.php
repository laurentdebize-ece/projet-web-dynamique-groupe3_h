<?php
require_once 'src/database/DatabaseTable.php';

class Theme extends DatabaseTable
{
    const TABLE_NAME = 'Themes';
    const TABLE_TYPE = Theme::class;

    public function __construct($nomTheme)
    {
        $this->nomTheme = $nomTheme;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idTheme = null;

    #[TableOpt(Unique: true)]
    private string $nomTheme;
}
