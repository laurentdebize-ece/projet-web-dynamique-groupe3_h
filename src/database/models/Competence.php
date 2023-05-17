<?php
require_once 'src/database/DatabaseTable.php';

class Competence extends DatabaseTable
{
    const TABLE_NAME = 'Competences';
    const TABLE_TYPE = Competence::class;

    public function __construct($nomCompetences)
    {
        $this->nomCompetences = $nomCompetences;
        $this->dateCreation = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCompetences = null;

    #[TableOpt(Unique: true)]
    private string $nomCompetences;
    private DateTime $dateCreation;

    public static function getCompetencesByMatiere(DatabaseController $db, int $idMatiere): array
    {

        $table_matiere = Matiere::TABLE_NAME;
        $table_competences = Competence::TABLE_NAME;
        $table_matiere_competences = MatiereCompetences::TABLE_NAME;
        $matiereCompetence = array();
        $matiereCompetence['transverses'] = array();
        $matiereCompetence['specifiques'] = array();

        $competences = Competence::select($db,null,["JOIN $table_matiere_competences ON",
                                                "$table_matiere_competences.idCompetences = $table_competences.idCompetences",
                                                "JOIN $table_matiere ON",
                                                "$table_matiere.idMatiere = $table_matiere_competences.idMatiere",
                                                "WHERE","$table_matiere.idMatiere = $idMatiere"])->fetchAll();
        foreach ($competences as $competence)
        {
            $nomCompetence = $competence['nomCompetences'];
            if (in_array($nomCompetence,MatiereCompetences::getCompetencesTransverses($db)))
            {
                array_push($matiereCompetence['transverses'], $nomCompetence);
            }
            else
            {
                array_push($matiereCompetence['specifiques'], $nomCompetence);
            }
        }

        return $matiereCompetence;
    }

    /// ajoute une competence depuis un admin, ajoutez une condition sur les nomMatieres et nomThemes pour être sûr qu'ils existent
    private static function addCompetencesAdmin(DatabaseController $db, string $nomCompetences, array $nomMatieres, array $nomThemes): void
    {
        Competence::insert($db,new Competence($nomCompetences));
        $competences = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetences'","LIMIT 1"])->fetchTyped();
        var_dump($competences);
        $idCompetences = ClassQL::getObjectValues($competences)['idCompetences'];
        foreach ($nomMatieres as $nomMatiere)
        {
            $matiere = Matiere::select($db,null,["WHERE","nomMatiere = '$nomMatiere'","LIMIT 1"])->fetchTyped();
            var_dump($matiere);
            if($matiere){
                $idMatiere = ClassQL::getObjectValues($matiere)['idMatiere'];
                MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere));
            }
        }
        foreach ($nomThemes as $nomTheme)
        {
            $theme = Theme::select($db,null,["WHERE","nomTheme = '$nomTheme'","LIMIT 1"])->fetchTyped();
            var_dump($theme);
            if ($theme){
                $idTheme = ClassQL::getObjectValues($theme)['idTheme'];
                ThemesCompetences::insert($db,new ThemesCompetences($idCompetences,$idTheme));
            }
        }
    }

    private static function addCompetencesEleve(DatabaseController $db, int $idUser)
    {
        
    }

    /// ajoute une competence depuis un professeur, ajoutez une condition sur les nomMatieres et nomThemes pour être sûr qu'ils existent
    private static function addCompetenceProf(DatabaseController $db, int $idUser, string $nomCompetences, array $nomMatieres, array $nomThemes): void
    {
        $array = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
        var_dump($array);
        if (isset($nomCompetences)){
            foreach 
            ($nomMatieres as $nomMatiere)
            {
                if (in_array($nomMatiere,array_keys($array)) and !in_array($nomCompetences,$array)) {

                    try
                    {
                        !in_array($nomCompetences,Competence::select($db,"DISTINCT nomCompetences")->fetchAll())? Competence::insert($db,new Competence($nomCompetences)) : null;
                        $competence = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetences'","LIMIT 1"])->fetchTyped();
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
                    catch (Exception $e)
                    {
                        echo "Erreur lors de l'ajout de la compétence";
                    }
    
                }
            }
        }
        
    }


    public static function addCompetenceUser(DatabaseController $db, int $idUser, ?string $nomCompetences = null, ?array $nomMatieres = null, ?array $nomThemes = null): void
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);
        $competence = Competence::select($db,null,["WHERE","`nomCompetences` = '$nomCompetences'","LIMIT 1"])->fetchAll()[0];
        if (!$competence){
            switch($arrayUser['typeAccount'])
            {
                case User::ACCOUNT_TYPE_ADMIN:
                    self::addCompetencesAdmin($db, $nomCompetences, $nomMatieres, $nomThemes);
                    break;
                case User::ACCOUNT_TYPE_USER:
                    $competencesUser = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
                    $allCompetences = Competence::select($db,"DISTINCT nomCompetences")->fetchAll();

                    $missingCompetences = array();
                    foreach ($allCompetences as $competence)
                    {
                        $competenceName = $competence['nomCompetences'];
                        $found = false;
                        foreach ($competencesUser as $matiere => $competences)
                        {
                            if (in_array($competenceName, $competences)) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $missingCompetences[] = $competenceName;
                        }
                    }
                    /// FIXME: probleme avec le stockage des nouvelles compétences choisis par l'utilisateur -> insertion dans bdd?
                    /// missing competences correspond aux compétences disponibles pour l'utilisateur mais pas encore choisies

                case User::ACCOUNT_TYPE_PROF:
                    echo 'ok';
                    self::addCompetenceProf($db, $idUser, $nomCompetences, $nomMatieres, $nomThemes);
                    break;
            }
        }
    }
}
