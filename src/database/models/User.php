<?php
require_once 'src/database/DatabaseTable.php';

class User extends DatabaseTable
{
    const TABLE_NAME = 'Users';
    const TABLE_TYPE = User::class;

    public function __construct($email, $nomUser, $prenomUser, $hashPassword, $idEcole, $typeAccount)
    {
        $this->email = $email;
        $this->nomUser = $nomUser;
        $this->prenomUser = $prenomUser;
        $this->hashPassword = $hashPassword;
        $this->idEcole = $idEcole;
        $this->typeAccount = $typeAccount;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idUser = null;

    private int $typeAccount;
    private string $email;
    private string $nomUser;
    private string $prenomUser;
    private string $hashPassword;
    private int $idEcole;
}
