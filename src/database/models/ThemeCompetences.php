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

    /// retourne un tableau contenat les informations sur les matieres/competences/themes d'un utilisateur
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

    /// retourne un tableau avec les compétences d'un théme spécifié
    public static function groupCompetencesByTheme(DatabaseController $db, int $idTheme): ?array
    {
        $table_competences = Competence::TABLE_NAME;
        $table_competences_theme = ThemesCompetences::TABLE_NAME;

        $allcompetencesTheme = array();
        $competences = Competence::select($db,"DISTINCT $table_competences.idCompetences, $table_competences.nomCompetences",
                                            ["JOIN $table_competences_theme ON $table_competences_theme.idCompetences = $table_competences.idCompetences",
                                            "WHERE $table_competences_theme.idTheme = $idTheme"])->fetchAll();

        foreach ($competences as $competence)
        {
            $idCompetence = intval($competence['idCompetences']);
            $nomCompetence = $competence['nomCompetences'];
            $allcompetencesTheme[$idCompetence] = $nomCompetence;
        }
        
        return $allcompetencesTheme;
    }
}
