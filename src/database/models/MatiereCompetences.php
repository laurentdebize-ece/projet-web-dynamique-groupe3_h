<?php
require_once 'src/database/DatabaseTable.php';

class MatiereCompetences extends DatabaseTable
{
    const TABLE_NAME = 'MatiereCompetences';
    const TABLE_TYPE = MatiereCompetences::class;

    public function __construct(int $idCompetences, int $idMatiere)
    {
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiereCompetences = null;

    #[TableOpt(TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(TableForeignKey: Matiere::class)]
    private int $idMatiere;

    public static function getCompetencesTransverses(DatabaseController $db): array
    {
        $table_matiere = Matiere::TABLE_NAME;
        $table_competences = Competence::TABLE_NAME;
        $table_matiere_competences = MatiereCompetences::TABLE_NAME;

        $matiereCompetence = array();
        $competencesTransverses = array();

        $competences = Competence::select($db,null,
                                        ["JOIN $table_matiere_competences ON",
                                        "$table_matiere_competences.idCompetences = $table_competences.idCompetences",
                                        "JOIN $table_matiere ON",
                                        "$table_matiere.idMatiere = $table_matiere_competences.idMatiere"])->fetchAll();

        foreach ($competences as $competence)
        {
            $nomMatiere = $competence['nomMatiere'];
            $nomCompetence = $competence['nomCompetences'];
            
            if (!isset($matiereCompetence[$nomCompetence])) {
                $matiereCompetence[$nomCompetence] = array();
            }
            
            array_push($matiereCompetence[$nomCompetence], $nomMatiere);
        }
                        
        foreach($matiereCompetence as $competence => $matieres){
            if(count($matieres) > 1){
                array_push($competencesTransverses,$competence);
            }
        }
        return $competencesTransverses;
    }

    /// retourne un tableau matieres => compétences de l'utilisateur
    public static function getSubjectCompetencesUser(DatabaseController $db,int $idUser): ?array
    {
        $user = User::select($db, null, ["WHERE","`idUser` = $idUser","LIMIT 1"])->fetchTyped();
        if ($user === null) {
            return null;
        }
        $arrayUser = classQL::getObjectValues($user);

        $coursTable = Cours::TABLE_NAME;
        $matieresTable = Matiere::TABLE_NAME;
        $classesTable = Classe::TABLE_NAME;
        $userTable = User::TABLE_NAME;

        switch ($arrayUser['typeAccount'])
        {
            case User::ACCOUNT_TYPE_ADMIN:
                return null;

            case User::ACCOUNT_TYPE_USER:
                if (is_null($arrayUser['idClasse'])){
                    return null;
                }
                else{
                    $idClasse = $arrayUser['idClasse'];
                    $matieres = Matiere::select($db,"DISTINCT $matieresTable.idMatiere,nomMatiere",
                                                ["JOIN $coursTable ON $matieresTable.idMatiere = $coursTable.idMatiere",
                                                "JOIN $classesTable ON $classesTable.idClasse = $coursTable.idClasse",
                                                "WHERE $coursTable.idClasse = $idClasse"]
                                                )->fetchAll();
                    $matieresEleve = array();
                    foreach ($matieres as $matiere){
                        $competence = Competence::getCompetencesByMatiere($db,intval($matiere['idMatiere']));
                        $matieresEleve[$matiere['nomMatiere']] = $competence;
                    }
                    return $matieresEleve;
                }

            case User::ACCOUNT_TYPE_PROF:
                $matieres = Matiere::select($db,"DISTINCT $matieresTable.idMatiere,nomMatiere",
                                            ["JOIN $coursTable ON $matieresTable.idMatiere = $coursTable.idMatiere",
                                            "JOIN $userTable ON $userTable.idUser = $coursTable.idProfesseur",
                                            "WHERE $userTable.idUser = $idUser"]
                                            )->fetchAll();
                $matieresProf = array();
                foreach ($matieres as $matiere){
                    $competence = Competence::getCompetencesByMatiere($db,intval($matiere['idMatiere']));
                    $matieresProf[$matiere['nomMatiere']] = $competence;
                }
                return $matieresProf;
        }
    }
}
