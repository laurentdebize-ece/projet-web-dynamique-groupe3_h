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

    public static function select(DatabaseController $db, ?string $selector = null, ?array $conds = null): TypedPDOStatement
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        $sel = ($selector != null ? $selector : "*");
        $clauses  = $conds != null ? implode(" ", $conds) : "";

        $sql = "SELECT " . $sel . " FROM `" . static::TABLE_NAME . "` " . $clauses . ";";
        $stmt = $db->queryTyped($sql, static::TABLE_TYPE);
        return $stmt;
    }

    /// Insère un objet dans la base de données.
    public static function insert(DatabaseController $db, DatabaseTable $object): void
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        if (classQL::ensureUnique($db,$object,false) === true){
            $sql = ClassQL::getInsertionString($object, static::TABLE_NAME);
            $db->getPDO()->exec($sql);
        }
    }

    /// Modifie un objet dans la base de données
    public static function modify(DatabaseController $db, DatabaseTable $object): void
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        if (classQL::ensureUnique($db,$object,false) === true){
            $sql = ClassQL::getUpdateString($object);
            $db->getPDO()->exec($sql);
        }
    }

    /// Enleve un element de la BDD
    public static function delete(DatabaseController $db, DatabaseTable $object): void
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
        $sql = ClassQL::getDeleteString($object);
        $db->getPDO()->exec($sql);
    }
}
