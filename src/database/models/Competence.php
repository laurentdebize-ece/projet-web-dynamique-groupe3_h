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

        $table_competences = Competence::TABLE_NAME;
        $table_user_competences = UserCompetence::TABLE_NAME;
        $table_user = User::TABLE_NAME;

        switch ($arrayUser['typeAccount']) {

            case User::ACCOUNT_TYPE_PROF:
            case User::ACCOUNT_TYPE_USER:

                $subjectCompetence = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
                foreach ($subjectCompetence as $subject => $competences) {
                    foreach ($competences[Competence::COMPETENCE_TYPE_TRANSVERSE] as $competence) {
                        $nomCompetenceSQL = classQL::escapeSQL($competence);
                        $idCompetence = intval(Competence::select($db, "idCompetences", ["WHERE", "nomCompetences = '$nomCompetenceSQL'","LIMIT 1"])->fetch()['idCompetences']);
                        $competencesTotal[$idCompetence] = $competence;
                    }
                    foreach ($competences[Competence::COMPETENCE_TYPE_MATIERE] as $competence) {
                        $nomCompetenceSQL = classQL::escapeSQL($competence);
                        $idCompetence = intval(Competence::select($db, "idCompetences", ["WHERE", "nomCompetences = '$nomCompetenceSQL'","LIMIT 1"])->fetch()['idCompetences']);
                        $competencesTotal[$idCompetence] =  $competence;
                    }
                }
                
                if ($arrayUser['typeAccount'] === User::ACCOUNT_TYPE_USER) {

                    $competencesOpts = UserCompetence::getOptionalsCompetences($db, $idUser);
                    $competencesTotal = array_merge($competencesTotal, $competencesOpts);
                }

                break;

            case User::ACCOUNT_TYPE_ADMIN:

                throw new Exception("Les admins n'ont pas de compétences");
                break;

        }
        if (!empty($competencesTotal)) {
            $competencesTotal = array_unique($competencesTotal);
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
        
        if (!empty($idThemes)){
            self::check_idThemeInput($db, array_keys(Theme::getAllThemes($db)), $idThemes);
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

    public static function modifyCompetenceUser(DatabaseController $db, int $idUser, int $idCompetences, ?array $idMatieres = null, ?array $idThemes = null)
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);
        $competence = Competence::select($db, null, ["WHERE","`idCompetences` = $idCompetences","LIMIT 1"])->fetchTyped();

        self::check_idMatieresInput($db, array_keys(Matiere::getAllSubjects($db)), $idMatieres);
        self::check_idThemeInput($db, array_keys(Theme::getAllThemes($db)), $idThemes);

        if ($competence !== null) {

            switch($arrayUser['typeAccount'])
            {
                case User::ACCOUNT_TYPE_ADMIN:

                    foreach ($idMatieres as $idMatiere)
                    {
                        $matiereCompetence = MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetch();
                        $matiereCompetence['idMatiere'] = $idMatiere;
                        if (MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetch() === null) {
                            MatiereCompetences::modify($db, $matiereCompetence);
                        }
                        else {
                            throw new Exception("La doublette idCompetences => ". $matiereCompetence['idCompetences'] . "idMatiere =>" . $matiereCompetence['idMatiere'] ."existe déjà");
                        }
                    }

                    foreach ($idThemes as $idTheme)
                    {
                        $themeCompetence = ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetch();
                        $themeCompetence['idTheme'] = $idTheme;
                        if (ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetch() === null) {
                            ThemesCompetences::modify($db, $themeCompetence);
                        }
                        else {
                            throw new Exception("La doublette idCompetences => ". $themeCompetence['idCompetences'] . "idTheme =>" . $themeCompetence['idTheme'] ."existe déjà");
                        }
                    }

                    break;
                case User::ACCOUNT_TYPE_USER:

                    throw new Exception("Les élèves n'ont pas les droits pour modifier une compétence");
                    break;
                case User::ACCOUNT_TYPE_PROF:

                    self::check_idMatieresInput($db, array_keys(Matiere::getAllSubjectsUser($db, $idUser)), $idMatieres);

                    foreach ($idMatieres as $idMatiere)
                    {
                        $matiereCompetence = MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetch();
                        $matiereCompetence['idMatiere'] = $idMatiere;
                        if (MatiereCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idMatiere` = $idMatiere", "LIMIT 1"])->fetch() === null) {
                            MatiereCompetences::modify($db, $matiereCompetence);
                        }
                        else {
                            throw new Exception("La doublette idCompetences => ". $matiereCompetence['idCompetences'] . "idMatiere =>" . $matiereCompetence['idMatiere'] ."existe déjà");
                        }
                    }

                    foreach ($idThemes as $idTheme)
                    {
                        $themeCompetence = ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetch();
                        $themeCompetence['idTheme'] = $idTheme;
                        if (ThemesCompetences::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "AND", "`idTheme` = $idTheme", "LIMIT 1"])->fetch() === null) {
                            ThemesCompetences::modify($db, $themeCompetence);
                        }
                        else {
                            throw new Exception("La doublette idCompetences => ". $themeCompetence['idCompetences'] . "idTheme =>" . $themeCompetence['idTheme'] ."existe déjà");
                        }
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
                        if (!array_keys(MatiereCompetences::getCompetencesTransverses($db), $idCompetences)) {
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
