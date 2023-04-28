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

class UserController extends DbTableManager {
    public function __construct() {
        parent::__construct('Users', User::class);
    }
}

class User {
    private string $email;

    private int $typeAccount;
    private int $ecole;
    
    private string $nomUser;
    private string $prenom;
    private string $password;
    
}

class Ecole {
    private int $idEcole;

    private string $nomEcole;
    private string $typeEtude;
}

class Matiere {
    private int $idMatiere;

    private string $nomMatiere;
    private string $volumeHoraire;
}

class Classes {
    private int $groupe;
    private int $ecole;
    private int $annee;
}

class Promo {
    private string $nomPromo;
    
    private string $annee;
}

class



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
