<?php

require_once 'src/database/Database.php';

/// Représente une table de la base de données.
/// ATTENTION : La table doit posséder un constructeur publique vide
/// et les propriétés de la table doivent être mises a une valeur par défaut.
abstract class DatabaseTable
{
    /// Nom de la table dans la base de données.
    const TABLE_NAME = self::TABLE_NAME;
    /// Nom de la classe PHP représentant la table.
    const TABLE_TYPE = self::TABLE_TYPE;

    //TODO: ajouter des méthodes pour insérer, supprimer, modifier des données dans la table.

    public static function select(DatabaseController $db, ?string $selector): array
    {
        if (!$db->check_table_exists(static::TABLE_NAME)) {
            $db->createTable(static::TABLE_NAME, static::TABLE_TYPE);
        }
        $sql = "SELECT * FROM `" . static::TABLE_NAME . "`;";
        $stmt = $db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;
    }

    /// Insère un objet dans la base de données.
    public static function insert(DatabaseController $db, DatabaseTable $object): void
    {
        if (!$db->check_table_exists(static::TABLE_NAME)) {
            $db->createTable(static::TABLE_NAME, static::TABLE_TYPE);
        }
        $sql = ClassQL::getInsertionString($object, static::TABLE_NAME);
        $db->get_pdo()->exec($sql);
    }

    /// Crée un objet de la classe représentant la table à partir d'un tableau associatif de champs.
    public static function fromFields(array $fields): DatabaseTable
    {
        $class = new ReflectionClass(static::TABLE_TYPE);
        $obj = $class->newInstanceWithoutConstructor();
        foreach ($fields as $key => $value) {
            try {
                $prop = $class->getProperty($key);
                $prop->setAccessible(true);
                switch ($prop->getType()) {
                    case "DateTime":
                        $prop->setValue($obj, new DateTime($value));
                        break;
                    default:
                        $prop->setValue($obj, $value);
                        break;
                }
            } catch (ReflectionException $e) {
                continue;
            }
        }
        return $obj;
    }
}
