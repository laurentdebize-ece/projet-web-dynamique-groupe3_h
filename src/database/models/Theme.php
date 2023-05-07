<?php
require_once 'src/database/DatabaseTable.php';

class Theme extends DatabaseTable
{
    const TABLE_NAME = 'Themes';
    const TABLE_TYPE = Theme::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idTheme = null;

    private string $nomTheme;
}
