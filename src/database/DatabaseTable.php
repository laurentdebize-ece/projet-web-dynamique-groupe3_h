<?php

require_once 'src/database/Database.php';

/// Représente une table de la base de données.
/// ATTENTION : 
/// les propriétés de la table doivent être mises a une valeur par défaut.
abstract class DatabaseTable
{
    /// Nom de la table dans la base de données.
    const TABLE_NAME = self::TABLE_NAME;
    /// Nom de la classe PHP représentant la table.
    const TABLE_TYPE = self::TABLE_TYPE;

    //TODO: ajouter des méthodes pour insérer, supprimer, modifier des données dans la table.

    public static function select(DatabaseController $db, ?string $selector): TypedPDOStatement
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        $sql = "SELECT " . ($selector != null ? ("(" . $selector . ")") : "*") . "FROM `" . static::TABLE_NAME . "`;";
        $stmt = $db->queryTyped($sql, static::TABLE_TYPE);
        return $stmt;
    }

    /// Insère un objet dans la base de données.
    public static function insert(DatabaseController $db, DatabaseTable $object): void
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        $sql = ClassQL::getInsertionString($object, static::TABLE_NAME);
        $db->getPDO()->exec($sql);
    }

    public static function modify(DatabaseController $db, DatabaseTable $object): void
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        $sql = ClassQL::getUpdateString($object);
        $db->getPDO()->exec($sql);
    }
}
