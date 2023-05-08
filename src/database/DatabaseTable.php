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
        $db->get_pdo()->exec($sql);
    }

    /// retourne la valeur de la clé primaire de la table
    public static function getPrimaryKeyValue(DatabaseTable $object): ?int
    {
        $class = new ReflectionClass($object);
        $properties = $class->getProperties();
        $primaryKeyValue = null;

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(TableOpt::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                if (isset($arguments['PrimaryKey']) && $arguments['PrimaryKey'] === true) {
                    $property->setAccessible(true);
                    $primaryKeyValue = intval($property->getValue($object));
                }
            }
            if ($primaryKeyValue !== null) {
                return $primaryKeyValue;
            }
        }

        if ($primaryKeyValue === null) {
            return null;
        }
    }

    /// Crée un objet de la classe représentant la table à partir d'un tableau associatif de champs.
    /// Si $index est spécifié, retourne l'objet dont la clé primaire correspond à $index.
    /// Si $index n'est pas spécifié, retourne tous les objets.
    //FIXME: deplacer cette fonction dans ClassQL ???
    public static function fromFields(array $object, ?int $index = null): array
    {
        $objs = array();
        foreach ($object as $fields) {
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
                } 
                catch (ReflectionException $e) {
                    echo "Error during reflection: " . $e->getMessage() . "<br>";
                    exit();
                }
            }
            if ($index === null) {
                array_push($objs, $obj);
            }
            else {
                $primaryKeyValue = static::getPrimaryKeyValue($obj);
                if ($primaryKeyValue === $index) {
                    $objs[] = $obj;
                }
            }
        }
        return $objs;
    }
}