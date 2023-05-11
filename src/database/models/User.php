<?php
require_once 'src/database/DatabaseTable.php';
require_once 'src/database/models/Classe.php';

class User extends DatabaseTable
{
    const TABLE_NAME = 'Users';
    const TABLE_TYPE = User::class;

    const ACCOUNT_TYPE_ADMIN = 0;
    const ACCOUNT_TYPE_USER = 1;
    const ACCOUNT_TYPE_PROF = 2;

    public function __construct(int $typeAccount, string $email, string $nomUser, string $prenomUser, $hashPassword, ?Classe $classe = null)
    {
        if ($typeAccount >= 3 || $typeAccount < 0)
            throw new InvalidArgumentException("Type de compte spécifié invalide");

        $this->typeAccount = $typeAccount;
        $this->email = $email;
        $this->nomUser = $nomUser;
        $this->prenomUser = $prenomUser;
        $this->hashPassword = $hashPassword;
        $this->idClasse = null;
    }

    /// Authentitfie un utilisateur.
    /// Retourne les valeurs suivantes:
    /// - null si EMAIL / MDP incorrects.
    /// - Le `User` qui a tenté de s'authentifier.
    /*
        @param $db LA BBDD
    */
    public static function authenticate(DatabaseController $db, string $email, string $hashMdp): ?User
    {
        // $hashMdp = password_hash($mdp, PASSWORD_BCRYPT);
        //SAFETY: Le LIMIT 1 est censé limiter le nombre de résultats à 1.
        $user = User::select($db, null, ["WHERE", "`email` = '$email'", "AND", "`hashPassword` = '$hashMdp'", "LIMIT 1"])->fetchAllTyped();
        if (count($user) != 0) {
            return $user[0];
        }
        return null;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idUser = null;

    private int $typeAccount;
    private string $email;
    private string $nomUser;
    private string $prenomUser;
    private string $hashPassword;

    #[TableOpt(TableForeignKey: Classe::class)]
    private ?int $idClasse = null;
}