<?php
require_once 'src/utils.php';
require_once 'src/database/ClassQL.php';
require_once 'src/database/TypedPDOStatement.php';

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

        $this->db_name = 'omnesmyskills';
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

    /// Initialisation des valeurs par défaut fichier config/default.sql
    public function initDefaultValues(): void
    {
        $sql = "SHOW TABLES FROM {$this->db_name}";
        $stmt = $this->db_pdo->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_lignes = 0;
        if($resultat) {
            foreach ($resultat as $result)
            {
                $table_name = $result["Tables_in_"."{$this->db_name}"];
                $resultat_comptage = $this->db_pdo->query("SELECT COUNT(*) AS total FROM `$table_name`");

                if ($resultat_comptage) {
                    $donnees_comptage = $resultat_comptage->fetch(PDO::FETCH_ASSOC);
                    $total_lignes += $donnees_comptage["total"];
                }
            }

            if ($total_lignes == 0) {
                try{
                    $this->db_pdo->exec(file_get_contents('./config/default.sql'));
                }
                catch (PDOException $e) {
                    echo $e->getMessage() . "<br>";
                }           
            }
        }
    }

    /// retourne le PDO de la database
    public function getPDO(): PDO
    {
        return $this->db_pdo;
    }

    /// Exécute une requête SQL et retourne le statement PDO typé pour utilisation.
    public function queryTyped(string $sql, string $className): TypedPDOStatement
    {
        $stmt = $this->db_pdo->prepare($sql);
        return new TypedPDOStatement($stmt, $className);
    }

    /// Exécute une requête SQL et retourne le statement PDO non-typé pour utilisation.
    public function queryUntyped(string $sql): PDOStatement
    {
        $stmt = $this->db_pdo->prepare($sql);
        return $stmt;
    }

    public function lastInsertId(): int
    {
        return $this->db_pdo->lastInsertId();
    }
}
