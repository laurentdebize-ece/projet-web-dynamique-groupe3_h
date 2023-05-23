<?php
require_once 'src/database/DatabaseTable.php';

class Matiere extends DatabaseTable
{
    const TABLE_NAME = 'Matieres';
    const TABLE_TYPE = Matiere::class;

    public function __construct(string $nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiere = null;

    #[TableOpt(Unique: true)]
    private string $nomMatiere;

    public function getNom(): string {
        return $this->nomMatiere;
    }

    public static function getAllSubjects(DatabaseController $db): ?array
    {
        $allmatieres = array();
        $matieres = Matiere::select($db,null,["ORDER BY idMatiere ASC"])->fetchAll();
        foreach ($matieres as $matiere){
            $idMatiere = intval($matiere['idMatiere']);
            $allmatieres[$idMatiere] = $matiere['nomMatiere'];
        }
        return $allmatieres;
    }

    /// retourne toutes les matières d'un utilisateur (prof ou élève) idMatieres => nomMatiere
    public static function getAllSubjectsUser(DatabaseController $db, int $idUser): ?array
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        $user = classQL::getObjectValues($user);

        $table_matiere = Matiere::TABLE_NAME;
        $table_cours = Cours::TABLE_NAME;
        $table_user = User::TABLE_NAME;
        $table_classe = Classe::TABLE_NAME;

        switch ($user['typeAccount']) {

            case User::ACCOUNT_TYPE_USER:

                $idClasseUser = $user['idClasse'];
                $matieres = Matiere::select($db,"DISTINCT nomMatiere, Matieres.idMatiere",
                                            [
                                                "JOIN $table_cours ON $table_matiere.idMatiere = $table_cours.idMatiere",
                                                "JOIN $table_classe ON $table_classe.idClasse = $table_cours.idClasse",
                                                "WHERE $table_cours.idClasse = $idClasseUser"
                                            ]
                )->fetchAll();

                $matieresUser = array();
                foreach ($matieres as $matiere) {
                    $idMatiere = intval($matiere['idMatiere']);
                    $matieresUser[$idMatiere] = $matiere['nomMatiere'];
                }

                return $matieresUser;

            case User::ACCOUNT_TYPE_PROF:

                $idProf = $user['idUser'];
                $matieres = Matiere::select($db,"DISTINCT nomMatiere, Matieres.idMatiere",
                                            [
                                                "JOIN $table_cours ON $table_matiere.idMatiere = $table_cours.idMatiere",
                                                "JOIN $table_user ON $table_user.idUser = $table_cours.idProfesseur",
                                                "WHERE $table_cours.idProfesseur = $idProf"
                                            ]
                )->fetchAll();
                
                $matieresUser = array();
                foreach ($matieres as $matiere) {
                    $idMatiere = intval($matiere['idMatiere']);
                    $matieresUser[$idMatiere] = $matiere['nomMatiere'];
                }
                return $matieresUser;
                
                case User::ACCOUNT_TYPE_ADMIN:
                    return null;
        }
    }

    public function getID() : ?int {
        return $this->idMatiere;
    }
 
}
