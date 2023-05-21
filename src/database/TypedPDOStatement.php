<?php
require_once 'src/database/ClassQL.php';


/// Représente un objet PDOStatement typé.
final class TypedPDOStatement
{
    private PDOStatement $stmt;
    private string $className;

    public function __construct(PDOStatement $stmt, string $className)
    {
        $this->stmt = $stmt;
        $this->className = $className;
        $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
        $this->stmt->execute();
    }

    /// Retourne tous les résultats de la requête sous forme d'un tableau d'objets.
    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }

    /// Retourne le résultat de la requête sous forme d'un tableau d'objets typés.
    public function fetchAllTyped(): array
    {
        return array_map(fn ($result) => ClassQL::createFromFields($result, $this->className), $this->stmt->fetchAll());
    }

    /// Retourne le résultat de la requête sous forme d'un objet typé.
    public function fetchTyped(): ?object
    {
        $obj = $this->stmt->fetch();
        if (is_null($obj) || $obj === false)
            return null;
        return ClassQL::createFromFields($obj, $this->className);
    }

    public function __destruct()
    {
        $this->stmt->closeCursor();
    }
}
