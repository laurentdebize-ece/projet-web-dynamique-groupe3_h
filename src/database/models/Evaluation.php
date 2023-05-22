<?php
require_once 'src/database/DatabaseTable.php';

class Evaluation extends DatabaseTable
{
    const TABLE_NAME = 'Evaluations';
    const TABLE_TYPE = Evaluation::class;

    public function __construct(string $dateAutoEvaluation, int $idEleve, int $idCompetences, int $idMatiere, int $AutoEvaluation = null, string $evaluationFinale = null, string $dateEvaluation = null)
    {
        $this->AutoEvaluation = $AutoEvaluation;
        $dateAutoEvaluation = DateTime::createFromFormat("Y-m-d", $dateAutoEvaluation, new DateTimeZone('Europe/Paris'));
        if (!$dateAutoEvaluation) {
            throw new Exception("Invalid date format");
        }
        $this->dateAutoEvaluation = new DateTime($dateAutoEvaluation->format("Y-m-d"), new DateTimeZone('Europe/Paris'));
        $this->validation = false;
        $this->evaluationFinale = $evaluationFinale;
        if($dateEvaluation){
            $dateEvaluation = DateTime::createFromFormat("Y-m-d",$dateEvaluation, new DateTimeZone('Europe/Paris'));
            if (!$dateEvaluation){
                throw new Exception("Invalid date format");
            }
            $this->dateEvaluation = new DateTime($dateEvaluation->format("Y-m-d"), new DateTimeZone('Europe/Paris'));
        }
        else{
            $this->dateEvaluation = $dateEvaluation;
        }
        $this->idEleve = $idEleve;
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEvaluation = null;

    private ?int $AutoEvaluation;
    private DateTime $dateAutoEvaluation;

    private bool $validation;
    private ?string $evaluationFinale;
    private ?DateTime $dateEvaluation;

    #[TableOpt(TableForeignKey: User::class)]
    private int $idEleve;
    #[TableOpt(TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(TableForeignKey: Matiere::class)]
    private int $idMatiere;

    public static function getAllEvaluation(DatabaseController $db): array
    {
        $evaluation = Evaluation::select($db, null)->fetchAll();
        return $evaluation;
    }

    private static function ensureUniqueEval(DatabaseController $db, DateTime $dateButoir, int $idEleve, int $idMatiere, int $idCompetences): void
    {
        $evaluations = self::getAllEvaluation($db);
        if (count(array_filter($evaluations, fn ($evaluation) => intval($evaluation['idEleve']) === intval($idEleve)  && intval($evaluation['idCompetences']) === intval($idCompetences) && intval($evaluation['idMatiere']) === intval($idMatiere))) > 0) {
            throw new Exception ("this Evaluation already exists => idEleve: $idEleve, idMatiere: $idMatiere, idCompetences: $idCompetences");
        }
        else {
            $evaluation = new Evaluation($dateButoir->format("Y-m-d"),$idEleve,$idCompetences,$idMatiere);
            Evaluation::insert($db,$evaluation);
        }
    }

    public static function createEvaluationUser(DatabaseController $db, int $idUser, int $idCompetences, string $dateButoir, int $idPromo = null)
    {
        $table_matiere = Matiere::TABLE_NAME;
        $table_competences = Competence::TABLE_NAME;
        $table_matiere_competences = MatiereCompetences::TABLE_NAME;
        $table_classe = Classe::TABLE_NAME;
        $table_user = User::TABLE_NAME;
        $table_cours = Cours::TABLE_NAME;

        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        $competence = Competence::select($db, null, ["WHERE", "`idCompetences` = $idCompetences", "LIMIT 1"])->fetchTyped();
        $nomCompetence = classQL::getObjectValues($competence)['nomCompetences'];

        if ($user !== null and $competence !== null){
            switch($user->getAccountType()) {

                case User::ACCOUNT_TYPE_ADMIN:

                    if ($idPromo !== null){
                        $dateButoir = DateTime::createFromFormat("Y-m-d", $dateButoir, new DateTimeZone('Europe/Paris'));
                        if (!$dateButoir) {
                            throw new Exception("Invalid date format");
                        }

                        if(in_array($nomCompetence,MatiereCompetences::getCompetencesTransverses($db))){
                            
                            $matieres = Matiere::select($db,null,["JOIN $table_matiere_competences ON",
                                                                    "$table_matiere_competences.idMatiere = $table_matiere.idMatiere",
                                                                    "JOIN $table_competences ON",
                                                                    "$table_competences.idCompetences = $table_matiere_competences.idCompetences",
                                                                    "WHERE","$table_competences.idCompetences = $idCompetences"])->fetchAll();

                            $eleves = User::select($db,null,["JOIN $table_classe ON",
                                                            "$table_classe.idClasse = $table_user.idClasse",
                                                            "WHERE $table_classe.idPromo = $idPromo"])->fetchAll();

                            foreach($eleves as $eleve){
                                foreach($matieres as $matiere) {
                                    $idEleve = $eleve['idUser'];
                                    $idMatiere = $matiere['idMatiere'];
                                    self::ensureUniqueEval($db,$dateButoir,$idEleve,$idMatiere,$idCompetences);
                                }
                            }
                        }
                        else {
                            throw new Exception ("La compétence" . $nomCompetence . "n'est pas une compétence tranverse");
                        }
                    }
                    else{
                        throw new Exception("IdPromo must be specified");
                    }
                    break;

                case User::ACCOUNT_TYPE_PROF:

                    if ($idPromo !== null){
                        $dateButoir = DateTime::createFromFormat("Y-m-d", $dateButoir, new DateTimeZone('Europe/Paris'));
                        if (!$dateButoir) {
                            throw new Exception("Invalid date format");
                        }

                        $classes = array();
                        $classesProf = Classe::select($db,"DISTINCT $table_classe.idClasse",[
                                                            "JOIN $table_cours ON",
                                                            "$table_classe.idClasse = $table_cours.idClasse",
                                                            "JOIN $table_user ON",
                                                            "$table_cours.idProfesseur = $table_user.idUser",
                                                            "WHERE $table_user.idUser = $idUser AND $table_classe.idPromo = $idPromo"
                                                            ])->fetchAll();
                        foreach($classesProf as $classeProf)
                        {
                            array_push($classes,intval($classeProf['idClasse']));
                        }
                        $classes = implode(",",$classes);
                        $eleves = User::select($db,null,[
                                                    "WHERE $table_user.idClasse IN ($classes)"
                                                    ])->fetchAll();

                        $matiere = ClassQL::getObjectValues(Matiere::select($db,null,["JOIN $table_matiere_competences ON",
                                                            "$table_matiere_competences.idMatiere = $table_matiere.idMatiere",
                                                            "WHERE", "$table_matiere_competences.idCompetences = $idCompetences", "LIMIT 1"])->fetchTyped());
                                                            $nomMatiere = $matiere['nomMatiere'];

                        $idMatiere = $matiere['idMatiere'];
                        $subjectCompetencesProf = MatiereCompetences::getSubjectCompetencesUser($db,$idUser);
                        if (in_array($nomMatiere,array_keys($subjectCompetencesProf))){
                            $competences = array_merge($subjectCompetencesProf[$nomMatiere][Competence::COMPETENCE_TYPE_TRANSVERSE],$subjectCompetencesProf[$nomMatiere][Competence::COMPETENCE_TYPE_MATIERE]);
                            if(!empty($competences)){
                                if (in_array($nomCompetence,$competences)){
                                    foreach ($eleves as $eleve){
                                        $idEleve = $eleve['idUser'];
                                        self::ensureUniqueEval($db,$dateButoir,$idEleve,$idMatiere,$idCompetences);
                                    }
                                }
                                else{
                                    throw new Exception("la competence " . "`$nomCompetence`" . " ne correspond pas à la matiére donnée " . "`$nomMatiere`");
                                }
                            }
                            else{
                                throw new Exception("Les matières du professeur spécifiés n'ont pas de compétnces assignées");
                            }
                        }
                        else{
                            $user = classQL::getObjectValues($user);
                            throw new Exception("la matiere " . "`$nomMatiere`" . " n'est pas enseigné par le porfesseur " . $user['nomUser'] . " " . $user['prenomUser']); 
                        }
                    } 
                    else{
                        throw new Exception("IdPromo must be specified");
                    }
                    break;

                case User::ACCOUNT_TYPE_USER:
                    // à compléter
                    break;

                default:
                    break;
            }
        }
    }
}
