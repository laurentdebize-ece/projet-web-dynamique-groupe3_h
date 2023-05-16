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

        $competences = Competence::select($db,null,["JOIN $table_matiere_competences ON",
                                                "$table_matiere_competences.idCompetences = $table_competences.idCompetences",
                                                "JOIN $table_matiere ON",
                                                "$table_matiere.idMatiere = $table_matiere_competences.idMatiere",
                                                "WHERE","$table_matiere.idMatiere = $idMatiere"])->fetchAll();
        foreach ($competences as $competence)
        {
            $nomCompetence = $competence['nomCompetences'];
            array_push($matiereCompetence, $nomCompetence);
        }

        return $matiereCompetence;
    }


    public static function addCompetenceUser(DatabaseController $db, int $idUser, ?string $nomCompetences = null, ?string $nomMatiere = null)
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        $arrayUser = classQL::getObjectValues($user);

        switch($arrayUser['typeAccount'])
        {
            case User::ACCOUNT_TYPE_ADMIN:
                // ajout insert competence en fonction from html post
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
                return $missingCompetences;
            case User::ACCOUNT_TYPE_PROF:
                $array = MatiereCompetences::getSubjectCompetencesUser($db, $idUser);
                if (isset($nomCompetences) && isset($nomMatiere)){
                    if (in_array($nomMatiere,array_keys(MatiereCompetences::getSubjectCompetencesUser($db,$idUser)))) {
                        !in_array($nomCompetences,Competence::select($db,"DISTINCT nomCompetences")->fetchAll())? Competence::insert($db,new Competence($nomCompetences)) : null;
                        $competences = Competence::select($db,null,["WHERE","nomCompetences = '$nomCompetences'","LIMIT 1"])->fetchTyped();
                        $idCompetences = ClassQL::getObjectValues($competences)['idCompetences'];
                        $matieres = Matiere::select($db,null,["WHERE","nomMatiere = '$nomMatiere'","LIMIT 1"])->fetchTyped();
                        $idMatiere = ClassQL::getObjectValues($matieres)['idMatiere'];
                        !in_array($nomCompetences,$array)? MatiereCompetences::insert($db,new MatiereCompetences($idCompetences,$idMatiere)): null;
                    }
                }
        }
    }
}
