<?php
require_once 'src/database/DatabaseTable.php';

class Theme extends DatabaseTable
{
    const TABLE_NAME = 'Themes';
    const TABLE_TYPE = Theme::class;

    public function __construct(string $nomTheme)
    {
        $this->nomTheme = $nomTheme;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idTheme = null;

    #[TableOpt(Unique: true)]
    private string $nomTheme;

    public static function getAllThemes(DatabaseController $db): ?array
    {
        $allthemes = array();
        $themes = Theme::select($db,null,["ORDER BY idTheme ASC"])->fetchAll();
        foreach ($themes as $theme)
        {
            $nomTheme = $theme['nomTheme'];
            $idTheme = intval($theme['idTheme']);
            $allthemes[$idTheme] = $nomTheme;
        }
        return $allthemes;
    }

    public static function getThemesByCompetences(DatabaseController $db, int $idCompetences): array
    {

        $table_themes = Theme::TABLE_NAME;
        $table_competences = Competence::TABLE_NAME;
        $table_themes_competences = ThemesCompetences::TABLE_NAME;
        $CompetenceTheme = array();

        $themes = Competence::select($db,null,["JOIN $table_themes_competences ON",
                                                "$table_themes_competences.idCompetences = $table_competences.idCompetences",
                                                "JOIN $table_themes ON",
                                                "$table_themes.idTheme = $table_themes_competences.idTheme",
                                                "WHERE","$table_competences.idCompetences = $idCompetences"])->fetchAll();
        foreach ($themes as $theme)
        {
            $nomTheme = $theme['nomTheme'];
            array_push($CompetenceTheme, $nomTheme);
        }

        return $CompetenceTheme;
    }
}
