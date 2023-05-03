<?php

require_once 'src/database/Database.php';

/// Représente une table de la base de données.
abstract class DatabaseTable
{
    /// Nom de la table dans la base de données.
    const TABLE_NAME = self::TABLE_NAME;
    /// Nom de la classe PHP représentant la table.
    const TABLE_TYPE = self::TABLE_TYPE;

    //TODO: ajouter des méthodes pour insérer, supprimer, modifier des données dans la table.

    public static function select(DatabaseController $db,?string $selector): array
    {
        if (!$db->check_table_exists(static::TABLE_NAME)){
            $db->createTable(static::TABLE_NAME, static::TABLE_TYPE);
        }      
        $sql = "SELECT * FROM `" . static::TABLE_NAME . "`;";
        $stmt = $db->query($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        return $results;
    }

    public static function insert(DatabaseController $db,DatabaseTable $object): void
    {
        if (!$db->check_table_exists(static::TABLE_NAME)){
            $db->createTable(static::TABLE_NAME, static::TABLE_TYPE);
        } 
        $sql = ClassQL::getInsertionString($object, static::TABLE_NAME);
        echo $sql;
        $db->get_pdo()->exec($sql);
    }
}
