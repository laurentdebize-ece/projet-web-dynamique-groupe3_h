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
    //TODO: faire en sorte que toutes les fonctions retournent un SQLTypedStatement qui permet de parcourir les résultats de la requête en utilisant foreach avec des objets typesafe.

    public static function select(DatabaseController $db, ?string $selector): array
    {
        $db->ensureTableExists(static::TABLE_NAME, static::TABLE_TYPE);
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

    /// Crée un objet de la classe représentant la table à partir d'un tableau associatif de champs.
    //FIXME: deplacer cette fonction dans ClassQL ???
}

// begin_session();
