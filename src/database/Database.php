<?php
require_once 'src/utils.php';
require_once 'src/database/ClassQL.php';


/// Contrôle l'accès à la base de données.
class DatabaseController
{
    static private ?DatabaseController $instance = null;
    private ?PDO $db_pdo;
    public ?string $db_name;

    private function __construct()
    {
        [$dsn, $user, $password] = get_db_config();
        $this->db_pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $this->db_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->db_name = 'OmnesMySkills';
        $sql = "CREATE DATABASE IF NOT EXISTS {$this->db_name};
            USE {$this->db_name};
            ";
        try {
            $this->db_pdo->exec($sql);
        } catch (PDOException $e) {
            echo "Error creating database: " . $e->getMessage() . "<br>";
            $this->db_pdo = null;
            $this->db_name = null;
            exit();
        }
    }

    /// check si la DB contient une table donnée
    private function check_table_exists($table_name)
    {
        $sql = "SHOW TABLES FROM {$this->db_name} LIKE '$table_name'";
        $stmt = $this->db_pdo->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultat) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /// Crée ou retourne le singleton de l'instance DatabaseController
    public static function getInstance(): DatabaseController
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseController();
        }

        return self::$instance;
    }

    /// Crée une table avec le nom & le schéma de données spécifiées dans la BDD si celle-ci n'existe pas déjà.
    public function createTable(string $tableName, string $tableType): void
    {
        [$tableDef, $tableDeps] = ClassQL::getTableDefForClass($tableType);
        foreach ($tableDeps as $dep) {
            $this->ensureTableExists($dep::TABLE_NAME, $dep::TABLE_TYPE);
        }
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` ($tableDef);";
        $this->db_pdo->exec($sql);
    }

    /// Assure la création d'une table avec le nom & le schéma de données spécifiées dans la BDD.
    public function ensureTableExists(string $tableName, string $tableType): void
    {
        if (!$this->check_table_exists($tableName)) {
            $this->createTable($tableName, $tableType);
        }
    }

    /// Crée toutes les tables de la BDD
    public function initTables(): void
    {
        $all_classes = get_declared_classes();
        foreach ($all_classes as $classe) {
            if (is_subclass_of($classe, DatabaseTable::class)) {
                if ($classe::TABLE_NAME != null and $classe::TABLE_TYPE != null) {
                    $this->ensureTableExists($classe::TABLE_NAME, $classe::TABLE_TYPE);
                }
            }
        }
    }

    /// retourne le PDO de la database
    public function getPDO(): PDO
    {
        return $this->db_pdo;
    }

    /// Exécute une requête SQL et retourne le statement PDO pour utilisation.
    public function query(string $sql): PDOStatement
    {
        $stmt = $this->db_pdo->prepare($sql);
        return $stmt;
    }

    public function lastInsertId(): int
    {
        return $this->db_pdo->lastInsertId();
    }
}
