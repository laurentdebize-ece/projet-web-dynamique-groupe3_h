<?php
require_once 'src/database/DatabaseTable.php';

class ThemesCompetences extends DatabaseTable
{
    const TABLE_NAME = 'ThemesCompetences';
    const TABLE_TYPE = ThemesCompetences::class;

    public function __construct(int $idCompetences, int $idTheme)
    {
        $this->idCompetences = $idCompetences;
        $this->idTheme = $idTheme;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idThemesCompetences = null;

    #[TableOpt(TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(TableForeignKey: Theme::class)]
    private int $idTheme;

    public static function getSubjectCompetencesThemeUser(DatabaseController $db, int $idUser): array
    {
        $themesArray = array();
        $array = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
        foreach ($array as $matiere => $competences) {
            foreach ($competences[Competence::COMPETENCE_TYPE_TRANSVERSE] as [$competence, $idCompetence]) {
                $themes = Theme::getThemesByCompetences($db, $idCompetence);
                $themesArray[$matiere][$competence] = $themes;
            }
            foreach ($competences[Competence::COMPETENCE_TYPE_MATIERE] as [$competence, $idCompetence]) {
                $themes = Theme::getThemesByCompetences($db, $idCompetence);
                $themesArray[$matiere][$competence] = $themes;
            }
        }
        return $themesArray;
    }
}
