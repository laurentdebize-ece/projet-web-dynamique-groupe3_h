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
    public static function authenticate(DatabaseController $db, string $email, string $hashMdp): User|bool|null
    {
        //SAFETY: Le LIMIT 1 est censé limiter le nombre de résultats à 1.
        $user = User::select($db, null, ["WHERE", "`email` = '$email'", "LIMIT 1"])->fetchAllTyped();
        if (count($user) > 0) {
            if ($user[0]->login($hashMdp)) {
                return $user[0];
            }
            return false;
        }
        return null;
    }

    /// Vérifie si le mot de passe spécifié est correct et que l'utilisateur peut se connecter.
    public function login(string $hashMdp): bool
    {
        return password_verify($hashMdp, $this->hashPassword);
    }

    /// Retourne l'ID de l'utilisateur.
    public function getID(): int
    {
        return $this->idUser;
    }

    /// Retourne le nom d'affichage de l'utilisateur.
    public function getDisplayName(): string {
        return $this->prenomUser . " " . $this->nomUser;
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
