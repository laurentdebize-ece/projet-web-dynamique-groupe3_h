<?php
require_once 'src/database/DatabaseTable.php';

class Theme extends DatabaseTable
{
    const TABLE_NAME = 'Themes';
    const TABLE_TYPE = Theme::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idTheme = null;

    private string $nomTheme;
}


class Competence extends DatabaseTable
{
    const TABLE_NAME = 'Competences';
    const TABLE_TYPE = Competence::class;

    public function __construct($nomCompetences,$dateCreation)
    {
        $this->nomCompetences = $nomCompetences;
        $this->dateCreation = $dateCreation;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCompetences = null;

    private string $nomCompetences;

    #[TableOpt(date: true)]
    private string $dateCreation;
}

class ThemesCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Themes/Competences';
    const TABLE_TYPE = ThemesCompetences::class;

    private int $idCompetences;
    private int $idTheme;
}

class Matiere extends DatabaseTable
{
    const TABLE_NAME = 'Matieres';
    const TABLE_TYPE = Matiere::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiere = null;

    private string $nomMatiere;
    private string $idProfesseur;
}

class MatiereCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Matiere/Competences';
    const TABLE_TYPE = MatiereCompetences::class;

    private int $idCompetences;
    private int $idMatiere;
}

class Ecole extends DatabaseTable
{
    const TABLE_NAME = 'Ecoles';
    const TABLE_TYPE = Ecole::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEcole = null;

    private string $nomEcole;
    private string $typeEtude;
}

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idPromo = null;

    private int $annee;
    private string $statut;
    private int $idEcole;
}

class Cours extends DatabaseTable
{
    const TABLE_NAME = 'Cours';
    const TABLE_TYPE = Cours::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCours = null;

    private float $volumeHoraire;
    private int $idClasse;
    private int $idMatiere;
}

class Classe extends DatabaseTable
{
    const TABLE_NAME = 'Classes';
    const TABLE_TYPE = Classe::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idClasse = null;

    private int $numGroupe;
    private int $idPromo;
    private int $idEleve;
}

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

    #[TableOpt(Ignore:true)]
    private Ecole $ecole;
}

class Evaluation extends DatabaseTable
{
    const TABLE_NAME = 'Evaluations';
    const TABLE_TYPE = Evaluation::class;

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEvaluation = null;

    private string $AutoEvaluation;
    private string $dateAutoEvaluation;
    private bool $validation;
    private string $evaluationFinale;
    private string $dateEvaluation;
    private int $idUser;
    private int $idCompetences;
    private int $idMatiere;
}
