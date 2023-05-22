<?php
require_once 'src/database/DatabaseTable.php';
require_once 'src/database/models/Classe.php';

class UserCompetence extends DatabaseTable
{
    const TABLE_NAME = 'UserCompetences';
    const TABLE_TYPE = UserCompetence::class;

    public function __construct(int $idCompetences, int $idUser)
    {
        $this->idCompetences = $idCompetences;
        $this->idUser = $idUser;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idUserCompetences = null;

    #[TableOpt(TableForeignKey: Competence::class)]
    private int $idCompetences;
    #[TableOpt(TableForeignKey: User::class)]
    private int $idUser;

    public static function getOptionalsCompetences(DatabaseController $db,int $idUser): array
    {
        $table_competences = Competence::TABLE_NAME;
        $table_user_competences = UserCompetence::TABLE_NAME;
        $table_user = User::TABLE_NAME;

        $competences = Competence::select($db, null, [
                                        "JOIN $table_user_competences ON $table_user_competences.idCompetences = $table_competences.idCompetences",
                                        "JOIN $table_user ON $table_user.idUser = $table_user_competences.idUser",
                                        "WHERE $table_user.idUser = $idUser"
                                        ])->fetchAll();
        $competences = array_map(fn ($competence) => $competence['nomCompetences'], $competences);
        return $competences;
    }
}