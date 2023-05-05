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

    public function __construct($nomCompetences)
    {
        $this->nomCompetences = $nomCompetences;
        $this->dateCreation = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idCompetences = null;

    private string $nomCompetences;
    private DateTime $dateCreation;
}

class ThemesCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Themes/Competences';
    const TABLE_TYPE = ThemesCompetences::class;

    public function __construct($idCompetences, $idTheme)
    {
        $this->idCompetences = $idCompetences;
        $this->idTheme = $idTheme;
    }

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idCompetences;

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idTheme;
}

class Matiere extends DatabaseTable
{
    const TABLE_NAME = 'Matieres';
    const TABLE_TYPE = Matiere::class;

    public function __construct($nomMatiere, $idProfesseur)
    {
        $this->nomMatiere = $nomMatiere;
        $this->idProfesseur = $idProfesseur;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idMatiere = null;

    private string $nomMatiere;
    private string $idProfesseur;
}

class MatiereCompetences extends DatabaseTable
{
    const TABLE_NAME = 'Matiere/Competences';
    const TABLE_TYPE = MatiereCompetences::class;

    public function __construct($idCompetences, $idMatiere)
    {
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idCompetences;

    #[TableOpt(PrimaryKey: true, ForeignKey: true)]
    private int $idMatiere;
}

class Ecole extends DatabaseTable
{
    const TABLE_NAME = 'Ecoles';
    const TABLE_TYPE = Ecole::class;

    public function __construct($nomEcole, $typeEtude)
    {
        $this->nomEcole = $nomEcole;
        $this->typeEtude = $typeEtude;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEcole = null;

    private string $nomEcole;
    private string $typeEtude;
}

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    public function __construct($annee, $statut, $idEcole)
    {
        $this->annee = $annee;
        $this->statut = $statut;
        $this->idEcole = $idEcole;
    }

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

    public function __construct($volumeHoraire, $idClasse, $idMatiere)
    {
        $this->volumeHoraire = $volumeHoraire;
        $this->idClasse = $idClasse;
        $this->idMatiere = $idMatiere;
    }

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

    public function __construct($numGroupe, $idPromo, $idEleve)
    {
        $this->numGroupe = $numGroupe;
        $this->idPromo = $idPromo;
        $this->idEleve = $idEleve;
    }

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

    public function __construct($AutoEvaluation,  $idUser, $idCompetences, $idMatiere, $validation=null, $evaluationFinale=null, $dateEvaluation=null)
    {
        $this->AutoEvaluation = $AutoEvaluation;
        $this->dateAutoEvaluation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->validation = $validation;
        $this->evaluationFinale = $evaluationFinale;
        $this->dateEvaluation = $dateEvaluation;
        $this->idUser = $idUser;
        $this->idCompetences = $idCompetences;
        $this->idMatiere = $idMatiere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idEvaluation = null;

    private string $AutoEvaluation;
    private DateTime $dateAutoEvaluation;
    private ?bool $validation;
    private ?string $evaluationFinale;
    private ?DateTime $dateEvaluation;
    private int $idUser;
    private int $idCompetences;
    private int $idMatiere;
}
