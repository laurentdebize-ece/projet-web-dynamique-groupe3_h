<?php
require_once 'src/database/DatabaseTable.php';

class User extends DatabaseTable
{
    const TABLE_NAME = 'Users';
    const TABLE_TYPE = User::class;

    public function __construct($typeAccount, $email, $nomUser, $prenomUser, $hashPassword, $idClasse)
    {
        $this->typeAccount = $typeAccount;
        $this->email = $email;
        $this->nomUser = $nomUser;
        $this->prenomUser = $prenomUser;
        $this->hashPassword = $hashPassword;
        $this->idClasse = $idClasse;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idUser = null;

    private int $typeAccount;
    private string $email;
    private string $nomUser;
    private string $prenomUser;
    private string $hashPassword;

    #[TableOpt(TableForeignKey: Classe::class)]
    private int $idClasse;
}