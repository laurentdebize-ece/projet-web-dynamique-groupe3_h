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
    public static function getAllCompetences(DatabaseController $db):array
    {
        $competences = Competence::select($db,null,["ORDER BY","nomCompetences ASC"])->fetchAll();
        $competences = array_map(fn ($competence) => $competence['nomCompetences'], $competences);
        return $competences;
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

    /// ajoute une competence depuis un admin, ajoutez une condition sur les nomMatieres et nomThemes pour être sûr qu'ils existent
    private static function addCompetencesAdmin(DatabaseController $db, string $nomCompetences, array $nomMatieres, array $nomThemes): void
    {
        !in_array($nomCompetences,Competence::select($db,"DISTINCT nomCompetences")->fetchAll())? Competence::insert($db,new Competence($nomCompetences)) : null;
        $nomCompetence = classQL::escapeSQL($nomCompetences);
        $competences = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetence'","LIMIT 1"])->fetchTyped();
        if($competences !== null){
            $idCompetences = ClassQL::getObjectValues($competences)['idCompetences'];
            foreach ($nomMatieres as $nomMatiere)
            {
                $matiere = Matiere::select($db,null,["WHERE","nomMatiere = '$nomMatiere'","LIMIT 1"])->fetchTyped();
                if($matiere){
                    $idMatiere = ClassQL::getObjectValues($matiere)['idMatiere'];
                    MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere));
                }
            }
            foreach ($nomThemes as $nomTheme)
            {
                $theme = Theme::select($db,null,["WHERE","nomTheme = '$nomTheme'","LIMIT 1"])->fetchTyped();
                if ($theme){
                    $idTheme = ClassQL::getObjectValues($theme)['idTheme'];
                    ThemesCompetences::insert($db,new ThemesCompetences($idCompetences,$idTheme));
                }
            }
        }
    }

    private static function addCompetencesEleve(DatabaseController $db, int $idUser, int $idCompetences): void
    {
        $addCompetence = Competence::select($db, null, ["WHERE", "idCompetences = $idCompetences", "LIMIT 1"])->fetchTyped();

        if ($addCompetence !== null) {
            $nomCompetence = ClassQL::getObjectValues($addCompetence)['nomCompetences'];
            $competencesUser = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);

            if (in_array($nomCompetence,Competence::getAllCompetences($db)) and !in_array($nomCompetence, $competencesUser)) {
                $competence = new UserCompetence($idCompetences, $idUser);
                UserCompetence::insert($db, $competence);
            }
        }
    }

    /// ajoute une competence depuis un professeur, ajoutez une condition sur les nomMatieres et nomThemes pour être sûr qu'ils existent
    private static function addCompetenceProf(DatabaseController $db, int $idUser, string $nomCompetences, array $nomMatieres, array $nomThemes): void
    {
        $array = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
        if (isset($nomCompetences)){
            foreach 
            ($nomMatieres as $nomMatiere) {
                if (in_array($nomMatiere,array_keys($array)) and !in_array($nomCompetences,$array)) {

                    if (!in_array($nomCompetences,self::getAllCompetences($db))){
                        Competence::insert($db,new Competence($nomCompetences));
                    }
                    
                    $nomCompetenceSQL = classQL::escapeSQL($nomCompetences);
                    $competence = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetenceSQL'","LIMIT 1"])->fetchTyped();
                    var_dump($competence);
                    $idCompetences = ClassQL::getObjectValues($competence)['idCompetences'];
                    foreach ($nomMatieres as $nomMatiere)
                    {
                        $matiere = Matiere::select($db,null,["WHERE","nomMatiere = '$nomMatiere'","LIMIT 1"])->fetchTyped();
                        if($matiere)
                        {
                            $idMatiere = ClassQL::getObjectValues($matiere)['idMatiere'];
                            !in_array($nomCompetences,$array)? MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere)): null;
                        }
                    }
                    foreach ($nomThemes as $nomTheme)
                    {
                        $theme = Theme::select($db,null,["WHERE","nomTheme = '$nomTheme'","LIMIT 1"])->fetchTyped();
                        if ($theme)
                        {
                            $idTheme = ClassQL::getObjectValues($theme)['idTheme'];
                            !in_array($nomCompetences,$array)? ThemesCompetences::insert($db,new ThemesCompetences($idCompetences,$idTheme)): null;
                        }
                    }
                    
                }
            }
        }
    }

    public static function addCompetenceUser(DatabaseController $db, int $idUser, string $nomCompetences, ?array $nomMatieres = null, ?array $nomThemes = null): void
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);
        $nomCompetenceSQL = ClassQL::escapeSQL($nomCompetences);
        $competence = Competence::select($db,null,["WHERE","`nomCompetences` = '$nomCompetenceSQL'","LIMIT 1"])->fetchTyped();
        switch($arrayUser['typeAccount'])
        {
            case User::ACCOUNT_TYPE_ADMIN:
                self::addCompetencesAdmin($db, $nomCompetences, $nomMatieres, $nomThemes);
                break;
            case User::ACCOUNT_TYPE_USER:
                if($competence !== null){
                    $idCompetences = classQL::getObjectValues($competence)['idCompetences'];
                    self::addCompetencesEleve($db, $idUser, $idCompetences);
                }
                break;
            case User::ACCOUNT_TYPE_PROF:
                self::addCompetenceProf($db, $idUser, $nomCompetences, $nomMatieres, $nomThemes);
                break;
        }
    }
}
