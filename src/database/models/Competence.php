<?php
require_once 'src/database/DatabaseTable.php';

class Competence extends DatabaseTable
{
    const TABLE_NAME = 'Competences';
    const TABLE_TYPE = Competence::class;

    const COMPETENCE_TYPE_TRANSVERSE = 'transverse';
    const COMPETENCE_TYPE_MATIERE = 'matiere';


    public function __construct(string $nomCompetences)
    {
        $this->nomCompetences = $nomCompetences;
        $this->dateCreation = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCompetences = null;

    #[TableOpt(Unique: true)]
    private string $nomCompetences;
    private DateTime $dateCreation;

    // renvoie toutes les compétences
    public static function getAllCompetences(DatabaseController $db): ?array
    {
        $allcompetences = array();
        $competences = Competence::select($db,null,["ORDER BY idCompetences ASC"])->fetchAll();
        foreach ($competences as $competence)
        {
            $nomCompetence = $competence['nomCompetences'];
            $idCompetence = intval($competence['idCompetences']);
            $allcompetences[$idCompetence] = $nomCompetence;
        }
        return $allcompetences;
    }

    // renvoie toutes les compétences d'un utilisateur idCompetences => nomCompetences
    public static function getAllCompetencesUser(DatabaseController $db, int $idUser): ?array
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);

        $competencesTotal = array();

        switch ($arrayUser['typeAccount']) {

            case User::ACCOUNT_TYPE_PROF:
            case User::ACCOUNT_TYPE_USER:

                $subjectCompetence = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
                foreach ($subjectCompetence as $subject => $competences) {
                    foreach ($competences[Competence::COMPETENCE_TYPE_TRANSVERSE] as [$competence,$idCompetence]) {
                        $competencesTotal[$idCompetence] = $competence;
                    }
                    foreach ($competences[Competence::COMPETENCE_TYPE_MATIERE] as [$competence,$idCompetence]) {
                        $competencesTotal[$idCompetence] =  $competence;
                    }
                }
                if ($arrayUser['typeAccount'] === User::ACCOUNT_TYPE_USER) {

                    $competencesOpts = UserCompetence::getOptionalsCompetences($db, $idUser);
                    $competencesTotal = $competencesTotal + $competencesOpts;
                }

                break;

            case User::ACCOUNT_TYPE_ADMIN:

                throw new Exception("Les admins n'ont pas de compétences");
                break;

        }
        if (!empty($competencesTotal)) {
            $competencesTotal = array_unique($competencesTotal);
            ksort($competencesTotal);
        }
        
        return $competencesTotal;
    }

    // renvoie toutes les compétences transverses et spécifiques d'une matière 
    public static function getCompetencesByMatiere(DatabaseController $db, int $idMatiere): array
    {
        $table_matiere = Matiere::TABLE_NAME;
        $table_competences = Competence::TABLE_NAME;
        $table_matiere_competences = MatiereCompetences::TABLE_NAME;

        $matiereCompetence = array();
        $matiereCompetence[Competence::COMPETENCE_TYPE_TRANSVERSE] = array();
        $matiereCompetence[Competence::COMPETENCE_TYPE_MATIERE] = array();

        $competences = Competence::select($db,null,["JOIN $table_matiere_competences ON",
                                                "$table_matiere_competences.idCompetences = $table_competences.idCompetences",
                                                "JOIN $table_matiere ON",
                                                "$table_matiere.idMatiere = $table_matiere_competences.idMatiere",
                                                "WHERE","$table_matiere.idMatiere = $idMatiere"])->fetchAll();

        foreach ($competences as $competence)
        {
            $nomCompetence = $competence['nomCompetences'];
            $idCompetence = $competence['idCompetences'];
            if (in_array($nomCompetence,MatiereCompetences::getCompetencesTransverses($db)))
            {
                array_push($matiereCompetence[Competence::COMPETENCE_TYPE_TRANSVERSE], [$nomCompetence, $idCompetence]);
            }
            else
            {
                array_push($matiereCompetence[Competence::COMPETENCE_TYPE_MATIERE], [$nomCompetence, $idCompetence]);
            }
        }
        return $matiereCompetence;
    }

    // vérifie si les idMatieres et idThemes spécifiés existent et conviennent à l'utilisateur
    private static function check_idMatieresInput(DatabaseController $db, array $idMatieres, array $idMatieresInput): void
    {
        $matieresManquantes = array_diff($idMatieresInput, $idMatieres);
        if (!empty($matieresManquantes)) {
            $matieresManquantesString = implode(', ', $matieresManquantes);
            throw new Exception("Les matières spécifiées référencées par les id $matieresManquantesString n'existent pas ou ne conviennent pas à l'utilisateur");
        }

    }

    // vérifie si les idThemes spécifiés existent et conviennent à l'utilisateur
    private static function check_idThemeInput(DatabaseController $db, array $idThemes, array $idThemesInput): void
    {
        $themesManquants = array_diff($idThemesInput, $idThemes);
        if (!empty($themesManquants)) {
            $themesManquantsString = implode(', ', $themesManquants);
            throw new Exception("Les thèmes spécifiés référencés par les id $themesManquantsString n'existent pas ou ne conviennent pas à l'utilisateur");
        }
    }

    /// ajoute une competence depuis un admin
    private static function addCompetencesAdmin(DatabaseController $db, string $nomCompetences, array $idMatieres, array $idThemes): void
    {
        !in_array($nomCompetences,self::getAllCompetences($db))? Competence::insert($db,new Competence($nomCompetences)) : null;

        $nomCompetenceSQL = classQL::escapeSQL($nomCompetences);
        $competences = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetenceSQL'","LIMIT 1"])->fetchTyped();

        if($competences !== null){
            $idCompetences = ClassQL::getObjectValues($competences)['idCompetences'];
            foreach ($idMatieres as $idMatiere)
            {
                $matiereCompetence = MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetchTyped();
                if ($matiereCompetence === null) {
                    MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere));
                }
                else {
                    throw new Exception("La doublette idCompetences => $idCompetences idMatiere => $idMatiere existe déjà");
                }
            }
            foreach ($idThemes as $idTheme)
            {
                $themeCompetence = ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetchTyped();
                if ($themeCompetence === null) {
                    ThemesCompetences::insert($db,new ThemesCompetences($idCompetences,$idTheme));
                }
                else {
                    throw new Exception("La doublette idCompetences => $idCompetences idTheme => $idTheme existe déjà");
                }
            }
        }
        else {
            throw new Exception("Erreur lors de la création de la compétence " . $nomCompetences);
        }
    }

    /// ajoute une competence depuis un eleve
    private static function addCompetencesEleve(DatabaseController $db, int $idUser, int $idCompetences): void
    {
        $addCompetence = Competence::select($db, null, ["WHERE", "idCompetences = $idCompetences", "LIMIT 1"])->fetchTyped();

        if ($addCompetence !== null) {
            $nomCompetence = ClassQL::getObjectValues($addCompetence)['nomCompetences'];

            if (!in_array($nomCompetence, self::getAllCompetencesUser($db, $idUser))) {
                $competence = new UserCompetence($idCompetences, $idUser);
                UserCompetence::insert($db, $competence);
            }
            else {
                throw new Exception("La compétence spécifiée est déjà assignée à l'utilisateur");
            }
        }
        else {
            throw new Exception("La compétence spécifiée n'existe pas => idCompetences = $idCompetences");
        }
    }

    /// ajoute une competence depuis un professeur
    private static function addCompetenceProf(DatabaseController $db, int $idUser, string $nomCompetences, array $idMatieres, array $idThemes): void
    {
        !in_array($nomCompetences,self::getAllCompetences($db))? Competence::insert($db,new Competence($nomCompetences)) : null;
        $idCompetences = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetences'","LIMIT 1"])->fetch()['idCompetences'];
        $matieresProf = Matiere::getAllSubjectsUser($db, $idUser);

        self::check_idMatieresInput($db, array_keys($matieresProf), $idMatieres);

        foreach ($idMatieres as $idMatiere)
        {
            $matiereCompetence = MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetchTyped();
            if ($matiereCompetence === null) {
                MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere));
            }
            else {
                throw new Exception("La doublette idCompetences => ". $matiereCompetence['idCompetences'] . "idMatiere =>" . $matiereCompetence['idMatiere'] ."existe déjà");
            }
        }

        foreach ($idThemes as $idTheme)
        {
            $themeCompetence = ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetchTyped();
            if ($themeCompetence === null) {
                ThemesCompetences::insert($db,new ThemesCompetences($idCompetences,$idTheme));
            }
            else {
                throw new Exception("La doublette idCompetences => ". $themeCompetence['idCompetences'] . "idTheme =>" . $themeCompetence['idTheme'] ."existe déjà");
            }
        }
    }

    /// ajoute une competence depuis un utilisateur, ajoutez une condition sur les nomMatieres et nomThemes pour être sûr qu'ils existent
    public static function addCompetenceUser(DatabaseController $db, int $idUser, string $nomCompetences, ?array $idMatieres = null, ?array $idThemes = null): void
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);

        $nomCompetenceSQL = ClassQL::escapeSQL($nomCompetences);
        $competence = Competence::select($db,null,["WHERE","`nomCompetences` = '$nomCompetenceSQL'","LIMIT 1"])->fetchTyped();

        if (!empty($idMatieres)){
            self::check_idMatieresInput($db, array_keys(Matiere::getAllSubjects($db)), $idMatieres);
        }
        else {
            throw new Exception("idMatieres est vide");
        }
        
        if (!empty($idThemes)){
            self::check_idThemeInput($db, array_keys(Theme::getAllThemes($db)), $idThemes);
        }
        else {
            throw new Exception("idThemes est vide");
        }
        

        switch($arrayUser['typeAccount'])
        {
            case User::ACCOUNT_TYPE_ADMIN:
                if (!empty($idMatieres) && !empty($idThemes)){
                    self::addCompetencesAdmin($db, $nomCompetences, $idMatieres, $idThemes);
                }
                else {
                    throw new Exception("Un admin ne peut pas ajouter une compétence sans matière précisée ou sans thème précisé");
                }
                break;
            case User::ACCOUNT_TYPE_USER:

                if($competence !== null){
                    $idCompetences = classQL::getObjectValues($competence)['idCompetences'];
                    self::addCompetencesEleve($db, $idUser, $idCompetences);
                }
                else {
                    throw new Exception("Un user ne peut pas s'ajouter une compétence qui n'existe pas");
                }
                break;
            case User::ACCOUNT_TYPE_PROF:
                if (!empty($idMatieres) && !empty($idThemes)){
                    self::addCompetenceProf($db, $idUser, $nomCompetences, $idMatieres, $idThemes);
                }
                else {
                    throw new Exception("Un professeur ne peut pas ajouter une compétence sans matière précisée ou sans thème précisé");
                }
                break;
        }
    }

    public static function modifyCompetenceUser(DatabaseController $db, int $idUser, int $idCompetences, int $idMatiereInitial, int $idThemeInitial, ?int $idMatiereTarget = null, ?int $idThemeTarget = null)
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);
        $competence = Competence::select($db, null, ["WHERE","`idCompetences` = $idCompetences","LIMIT 1"])->fetchTyped();

        $matiereCompetence = MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiereInitial", "LIMIT 1"])->fetch();
        if ($matiereCompetence === false) {
            throw new Exception("La matière spécifiée n'est pas assignée à la compétence");
        }
        $themeCompetence = ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idThemeInitial", "LIMIT 1"])->fetch();
        if ($themeCompetence === false) {
            throw new Exception("Le thème spécifié n'est pas assigné à la compétence");
        }

        if ($idMatiereTarget === null && $idThemeTarget === null) {
            throw new Exception("Vous devez spécifier au moins une matière ou un thème pour modifier une compétence");
        }

        if ($idMatiereTarget === $idMatiereInitial or $idThemeTarget === $idThemeInitial) {
            throw new Exception("Vous devez spécifier une matière ou un thème différent de la matière ou du thème initial");
        }
        
        if ($competence !== null) {

            switch($arrayUser['typeAccount'])
            {
                case User::ACCOUNT_TYPE_ADMIN:

                    if ($idMatiereTarget !== null) {
                        $matiereCompetence['idMatiere'] = $idMatiereTarget;
                        $matiereCompetenceInsert = (is_null(MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiereTarget", "LIMIT 1"])->fetchTyped()))
                                        ? classQL::createFromFields($matiereCompetence, MatiereCompetences::class)
                                        : null;
                        if ($matiereCompetenceInsert !== null) {
                            MatiereCompetences::modify($db, $matiereCompetenceInsert);
                        }
                        else {
                            MatiereCompetences::delete($db, classQL::createFromFields($matiereCompetence, MatiereCompetences::class));
                        }
                    }
                      
                    if ($idThemeTarget !== null) {
                        $themeCompetence['idTheme'] = $idThemeTarget;
                        $themeCompetenceInsert = (is_null(ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idThemeTarget", "LIMIT 1"])->fetchTyped()))
                                        ? classQL::createFromFields($themeCompetence, ThemesCompetences::class)
                                        : null;
                        if ($themeCompetenceInsert !== null) {
                        ThemesCompetences::modify($db, $themeCompetenceInsert);
                        }
                        else {
                            ThemesCompetences::delete($db, classQL::createFromFields($themeCompetence, ThemesCompetences::class));
                        }
                    }

                    break;
                case User::ACCOUNT_TYPE_USER:

                    throw new Exception("Les élèves n'ont pas les droits pour modifier une compétence");
                    break;
                case User::ACCOUNT_TYPE_PROF:

                    $arrayIdMatieres = [$idMatiereInitial,$idMatiereTarget];
                    self::check_idMatieresInput($db, array_keys(Matiere::getAllSubjectsUser($db, $idUser)), $arrayIdMatieres);

                    if (!in_array($idCompetences, array_keys(MatiereCompetences::getCompetencesTransverses($db)))) {
                        if ($idMatiereTarget !== null) {
                            $matiereCompetence['idMatiere'] = $idMatiereTarget;
                            $matiereCompetenceInsert = (is_null(MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiereTarget", "LIMIT 1"])->fetchTyped()))
                                            ? classQL::createFromFields($matiereCompetence, MatiereCompetences::class)
                                            : null;
                            if ($matiereCompetenceInsert !== null) {
                                MatiereCompetences::modify($db, $matiereCompetenceInsert);
                            }
                            else {
                                MatiereCompetences::delete($db, classQL::createFromFields($matiereCompetence, MatiereCompetences::class));
                            }
                        }

                        if ($idThemeTarget !== null) {
                            $themeCompetence['idTheme'] = $idThemeTarget;
                            $themeCompetenceInsert = (is_null(ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idThemeTarget", "LIMIT 1"])->fetchTyped()))
                                            ? classQL::createFromFields($themeCompetence, ThemesCompetences::class)
                                            : null;
                            if ($themeCompetenceInsert !== null) {
                            ThemesCompetences::modify($db, $themeCompetenceInsert);
                            }
                            else {
                                ThemesCompetences::delete($db, classQL::createFromFields($themeCompetence, ThemesCompetences::class));
                            }
                        }
                    }
                    else {
                        throw new Exception("Les professeurs ne peuvent pas modifier des compétences transverses");
                    }

                    break;
            }
        }
        else {
            throw new Exception("La compétence spécifiée n'existe pas");
        }
    }

    public static function deleteCompetenceUser(DatabaseController $db, int $idUser, int $idCompetences)
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);
        
        $competence = Competence::select($db, null, ["WHERE","`idCompetences` = $idCompetences","LIMIT 1"])->fetchTyped();
        if ($competence !== null) {
            switch($arrayUser['typeAccount'])
            {
                case User::ACCOUNT_TYPE_ADMIN:
                    Competence::delete($db, $competence);

                    break;
                case User::ACCOUNT_TYPE_USER:

                    throw new Exception("Les élèves n'ont pas les droits pour supprimer une compétence");
                    break;
                case User::ACCOUNT_TYPE_PROF:

                    if (in_array($idCompetences, self::getAllCompetencesUser($db, $idUser))) {
                        if (!in_array($idCompetences, array_keys(MatiereCompetences::getCompetencesTransverses($db)))) {
                            UserCompetence::delete($db, $competence);
                        }
                        else {
                            throw new Exception("Les professeurs ne peuvent pas supprimer des compétences transverses");
                        }
                    }
                    else {
                        throw new Exception("La compétence spécifiée n'est pas assignée au professeur");
                    }

                    break;
            }
        }
        else {
            throw new Exception("La compétence spécifiée n'existe pas");
        }
    }
}
