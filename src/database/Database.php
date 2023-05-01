<?
    require_once 'src/utils.php';


/// Contrôle l'accès à la base de données.
class DatabaseController
{
    static private ?DatabaseController $instance = null;
    private ?PDO $db_pdo;

    private function __construct()
    {
        [$dsn, $user, $password] = get_db_config();
        $this->db_pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $this->db_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): DatabaseController
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseController();
        }
        return self::$instance;
    }

    /// Crée une table avec le nom & le schéma de données spécifiées dans la BDD si celle-ci n'existe pas déjà.
    public function createTable(string $tableName, string $tableContents): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` ($tableContents);";
        $this->db_pdo->exec($sql);
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
