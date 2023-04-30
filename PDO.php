<?php

    include_once 'utils.php';

/// Class manager pour une table de la base de données
class DbTableManager {

    private string $table_name;
    private string $class_table_name;
    private ?PDO $db_pdo;

    protected function __construct(string $table_name, string $class_table_name) {

        // config des infos de table
        $this->table_name = $table_name;
        $this->class_table_name = $class_table_name;
        
        [$dsn, $user, $password] = get_db_config();

        try {
            $this->db_pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $this->db_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) {
            echo "Failed to connect: " . $e->getMessage() . "<br>";
            exit();
        }
    }

    public function get(?string $sql): mixed {
        
    }
}

class Theme {
    private int $idTheme;

    private string $nomTheme;
}

class ThemeController extends DbTableManager {
    public function __construct() {
        parent::__construct('Themes', Theme::class);
    }
}

class Competence {
    private int $idCompetences;

    private string $nomCompetences;
    private string $dateCreation;
}

class CompetenceController extends DbTableManager {
    public function __construct() {
        parent::__construct('Competences', Competence::class);
    }
}

class ThemesCompetences {
    private int $idCompetences;
    private int $idTheme;
}

class ThemeCompetencesController extends DbTableManager {
    public function __construct() {
        parent::__construct('Themes/Competences', ThemesCompetences::class);
    }
}

class Matiere {
    private int $idMatiere;

    private string $nomMatiere;
    private string $idProfesseur;
}

class MatiereController extends DbTableManager {
    public function __construct() {
        parent::__construct('Matieres', Matiere::class);
    }
}

class MatiereCompetences {
    private int $idCompetences;
    private int $idMatiere;
}

class MatiereCompetencesController extends DbTableManager {
    public function __construct() {
        parent::__construct('Matiere/Competences', MatiereCompetences::class);
    }
}

class Ecole {
    private int $idEcole;

    private string $nomEcole;
    private string $typeEtude;
}

class EcoleController extends DbTableManager {
    public function __construct() {
        parent::__construct('Ecoles', Ecole::class);
    }
}

class Promotion {
    private int $idPromo;

    private int $annee;
    private string $statut;
    private int $idEcole;
}

class PromotionController extends DbTableManager {
    public function __construct() {
        parent::__construct('Promotions', Promotion::class);
    }
}

class Cours {
    private int $idCours;

    private float $volumeHoraire;
    private int $idClasse;
    private int $idMatiere;
}

class CoursController extends DbTableManager {
    public function __construct() {
        parent::__construct('Cours', Cours::class);
    }
}

class Classe {
    private int $idClasse;

    private int $numGroupe;
    private int $idPromo;
    private int $idEleve;
}

class ClassesController extends DbTableManager {
    public function __construct() {
        parent::__construct('Classes', Classe::class);
    }
}

class User {
    private int $idUser;

    private int $typeAccount;
    private string $email;    
    private string $nomUser;
    private string $prenomUser;
    private string $hashPassword;
    private int $idEcole;
}

class UserController extends DbTableManager {
    public function __construct() {
        parent::__construct('Users', User::class);
    }
}

class Evaluation {
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

class EvaluationsController extends DbTableManager {
    public function __construct() {
        parent::__construct('Evaluations', Evaluation::class);
    }
}


class ControllerDB {
    private static $instance = null;
    private $DB_PDO = null;
    public $DB_name = null;
    public $table_name = null;

    private function __construct() {

        $jsonString = file_get_contents('../config/db.json');
        $config = json_decode($jsonString, true);
        if ($config === null) {
            die('Erreur lors de la lecture du fichier JSON');
        }

        $dsn = $config['credentials']['dsn'];
        $user = $config['credentials']['user'];
        $password = $config['credentials']['password'];

        try {
            $this->DB_PDO = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $this->DB_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Failed to connect: " . $e->getMessage() . "<br>";
            exit();
        }
    }

    public static function get_instance($DB_name, $table_name) {
        if (self::$instance == null) {
            self::$instance = new ControllerDB();
        }
        
        self::$instance->DB_name = $DB_name;
        self::$instance->table_name = $table_name;

        return self::$instance;
    }

    public function check_DB_exists($DB_name){
        $sql = "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?";
        $stmt = $this->DB_PDO->prepare($sql);
        $stmt->execute(array($DB_name));
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultat) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_table_exists($DB_name, $table_name){
        $sql = "SHOW TABLES FROM $DB_name LIKE ?";
        $stmt = $this->DB_PDO->prepare($sql);
        $stmt->execute([$table_name]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultat) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function create_DB_and_table(){
        if (!$this->check_DB_exists($this->DB_name)) {
            $sql = "CREATE DATABASE {$this->DB_name}";
            try {
                $this->DB_PDO->exec($sql);
            } 
            catch (PDOException $e) {
                echo "Error creating database: " . $e->getMessage() . "<br>";
                $this->DB_PDO = null;
                exit();
            }
        }
        if (!$this->check_table_exists($this->DB_name,$this->table_name)) {
            $this->DB_PDO->exec("USE {$this->DB_name}");
            $sql = "CREATE TABLE {$this->table_name} (
                    Id INT AUTO_INCREMENT PRIMARY KEY,
                    Montant DECIMAL(8,2) NOT NULL,
                    TypeAchat VARCHAR(25) NOT NULL,
                    DateAchat DATE NOT NULL
                    )";
            try {
                $this->DB_PDO->exec($sql);
            } catch (PDOException $e) {
                echo "Error creating table: " . $e->getMessage() . "<br>";
                $this->DB_PDO = null;
                exit();
            }
        }
    }
}
