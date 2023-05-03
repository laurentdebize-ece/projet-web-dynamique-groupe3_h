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

    public static function select(?string $selector): array
    {
        $db = DatabaseController::getInstance();
        $db->createTable(static::TABLE_NAME, static::TABLE_TYPE);
        $sql = "SELECT * FROM `" . static::TABLE_NAME . "`;";
        $stmt = $db->query($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::TABLE_TYPE);
        return $stmt->fetchAll();
    }

    public static function insert(DatabaseController $db, array $objs): void
    {
        $stringObj = "(" . implode(",", $objs) . ")";
        try{
            $sql = "INSERT INTO `" . static::TABLE_NAME . "` VALUES " . $stringObj . ";";
            echo $sql;
            $stmt = $db->query($sql);
            $stmt->execute();
            echo "Data inserted sucessfully";
        }
        catch (PDOException $e) {
            echo "Error inserting data: " . $e->getMessage() . "<br>";
            exit();
        }
        
    }
}
