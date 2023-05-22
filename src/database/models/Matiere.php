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

    public static function getAllSubjectsUser(DatabaseController $db, int $idUser): ?array
    {
        $user = User::select($db, null, ["WHERE", "`idUser` = $idUser", "LIMIT 1"])->fetchTyped();
        if ($user->getAccountType() === User::ACCOUNT_TYPE_USER) {
            if (is_null($user->getClasse())) {
                return null;
            } else {
                $idClasseUser = $user->getClasse();
                $matieres = Matiere::select(
                    $db,
                    'DISTINCT nomMatiere, Matieres.idMatiere',
                    [
                        "JOIN Cours ON Matieres.idMatiere = Cours.idMatiere",
                        "JOIN Classes ON Classes.idClasse = Cours.idClasse",
                        "WHERE Cours.idClasse = $idClasseUser"
                    ]
                )->fetchAll();
                $matieresUser = array();
                foreach ($matieres as $matiere) {
                    array_push($matieresUser, [$matiere['nomMatiere'], (int)$matiere['idMatiere']]);
                }
                return $matieresUser;
            }
        }
        else{
            return null;
        }
    }
}
