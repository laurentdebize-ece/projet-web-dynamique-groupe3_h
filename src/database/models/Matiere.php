<?php
require_once 'src/database/DatabaseTable.php';

class Matiere extends DatabaseTable
{
    const TABLE_NAME = 'Matieres';
    const TABLE_TYPE = Matiere::class;

    public function __construct($nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiere = null;

    #[TableOpt(Unique: true)]
    private string $nomMatiere;

    public static function getAllSubjects(DatabaseController $db): array|null
    {
        $idEleve = User::ACCOUNT_TYPE_USER;
        $users = User::select($db, null, ["WHERE","`typeAccount` = $idEleve"])->fetchAllTyped();
        $matieresUsers = array();
        foreach ($users as $user)
        {
            $arrayUser = classQL::getObjectValues($user);
            if (is_null($arrayUser['idClasse'])){
                return null;
            }
            else{
                $idClasseUser = $arrayUser['idClasse'];
                $matieres = Matiere::select($db,'DISTINCT nomMatiere',
                                            ["JOIN Cours ON Matieres.idMatiere = Cours.idMatiere",
                                            "JOIN Classes ON Classes.idClasse = Cours.idClasse",
                                            "WHERE Cours.idClasse = $idClasseUser"]
                                            )->fetchAll();
                $matieresUser = array();
                foreach ($matieres as $matiere){
                    array_push($matieresUser,$matiere['nomMatiere']);
                }
                $matieresUsers[$arrayUser['idUser']] = $matieresUser;
            }
        }
        return $matieresUsers;
    }
}
