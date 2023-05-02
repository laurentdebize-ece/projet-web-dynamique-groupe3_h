<?
require_once 'src/database/DatabaseTable.php';

class Theme extends DatabaseTable
{
    const TABLE_NAME = 'Themes';
    const TABLE_TYPE = Theme::class;

    private int $idTheme;

    private string $nomTheme;
}


class Competence extends DatabaseTable
{
    const TABLE_NAME = 'Competences';
    const TABLE_TYPE = Competence::class;

    private int $idCompetences;

    private string $nomCompetences;
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

    private int $idMatiere;

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

    private int $idEcole;

    private string $nomEcole;
    private string $typeEtude;
}

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    private int $idPromo;

    private int $annee;
    private string $statut;
    private int $idEcole;
}

class Cours extends DatabaseTable
{
    const TABLE_NAME = 'Cours';
    const TABLE_TYPE = Cours::class;

    private int $idCours;

    private float $volumeHoraire;
    private int $idClasse;
    private int $idMatiere;
}

class Classe extends DatabaseTable
{
    const TABLE_NAME = 'Classes';
    const TABLE_TYPE = Classe::class;

    private int $idClasse;

    private int $numGroupe;
    private int $idPromo;
    private int $idEleve;
}

class User extends DatabaseTable
{
    const TABLE_NAME = 'Users';
    const TABLE_TYPE = User::class;

    public function __construct($email, $nomUser, $prenomUser, $hashPassword, $idEcole = 0, $typeAccount = 0)
    {
        $this->email = $email;
        $this->nomUser = $nomUser;
        $this->prenomUser = $prenomUser;
        $this->hashPassword = $hashPassword;
        $this->idEcole = $idEcole;
        $this->typeAccount = $typeAccount;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private int $idUser = 0;

    private int $typeAccount;
    private string $email;
    private string $nomUser;
    private string $prenomUser;
    private string $hashPassword;
    private int $idEcole;
}

class Evaluation extends DatabaseTable
{
    const TABLE_NAME = 'Evaluations';
    const TABLE_TYPE = Evaluation::class;

    private int $idEvaluation;

    private string $AutoEvaluation;
    private string $dateAutoEvaluation;
    private bool $validation;
    private string $evaluationFinale;
    private string $dateEvaluation;
    private int $idUser;
    private int $idCompetences;
    private int $idMatiere;
}
