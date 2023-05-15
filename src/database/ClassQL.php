<?php

/// Un attribut à poser sur une propriété pour donner des paramètres supplémentaires à la propriété
#[Attribute]
class TableOpt
{
    /// Constructeur de l'attribut
    /// @param string $Type Le type de la propriété si on veut le spécifier manuellement. Si laissé à null, le type sera déduit automatiquement du type de la variable.
    /// @param bool $PrimaryKey Si la propriété est une clé primaire.
    public function __construct(
        public bool $Ignore = false,
        public bool $AutoIncrement = false,
        public bool $Unique = false,
        public bool $PrimaryKey = false,
        public ?string $TableForeignKey = null,
        public ?string $Type = null,
    ) {
    }
}


final class ClassQL
{

    /// tri et enlève les attributs avec le parametres 'Ignore'
    private static function include_field_comparator(ReflectionProperty $prop): bool
    {
        $attributes = $prop->getAttributes(TableOpt::class);
        foreach ($attributes as $attr) {
            if ($attr->getArguments()["Ignore"]) {
                return false;
            }
        }
        return true;
    }

    private static function get_table_primary_key(string $table): string
    {
        $refl = new ReflectionClass($table);
        $props = $refl->getProperties(ReflectionProperty::IS_PRIVATE);
        foreach ($props as $prop) {
            $attributes = $prop->getAttributes(TableOpt::class);
            foreach ($attributes as $attr) {
                if ($attr->getArguments()["PrimaryKey"]) {
                    return $prop->getName();
                }
            }
        }
        return null;
    }

    /// Retourne l'odre des tables à créer pour que les clés étrangères fonctionnent.
    private static function get_table_deps_recusrive(string $table, array $deps): array
    {
        $fields = self::enumerateTableFields($table);
        foreach ($fields as $field) {
            $attributes = $field->getAttributes(TableOpt::class);
            foreach ($attributes as $attr) {
                if (!is_null($attr->getArguments()["TableForeignKey"])) {
                    $table = $attr->getArguments()["TableForeignKey"];
                    $deps[] = $table;
                    $deps = self::get_table_deps_recusrive($table, $deps);
                }
            }
        }
        return $deps;
    }

    /// Retourne tous les champs utilisables pour créer une table SQL.
    public static function enumerateTableFields(string $table): array
    {
        $refl = new ReflectionClass($table);
        $props = $refl->getProperties(ReflectionProperty::IS_PRIVATE);

        return array_filter($props, 'self::include_field_comparator');
    }

    /// ajoute types supplémentaires incrémenté avec TableOpt pour sql
    private static function getSQLTypeDefForField(ReflectionProperty $prop): string
    {
        $type = self::getSQLTypeForField($prop);
        $attributes = $prop->getAttributes(TableOpt::class);
        foreach ($attributes as $attr) {

            if ($attr->getArguments()["PrimaryKey"]) {
                $type .= " NOT NULL PRIMARY KEY ";
            }

            if ($attr->getArguments()["AutoIncrement"]) {
                $type .= " AUTO_INCREMENT";
            }

            if ($attr->getArguments()["Unique"]) {
                $type .= " UNIQUE";
            }

            if (!is_null($attr->getArguments()["TableForeignKey"])) {

                $table = $attr->getArguments()["TableForeignKey"];
                $champForeign = self::get_table_primary_key($table);
                $champ = $prop->getName();
                $type .= ", FOREIGN KEY " . "($champ)" . " REFERENCES " . $table::TABLE_NAME . "($champForeign)";
            }
        }

        return $type;
    }

    /// transformer type php -> type sql
    public static function getSQLTypeForField(ReflectionProperty $prop): string
    {
        $baseType = $prop->getType()->getName();
        $isNullable = $prop->getType()->allowsNull();

        // on verifie que on n'a pas d'override de type spécifié sur la table.
        $attributes = $prop->getAttributes(TableOpt::class);
        foreach ($attributes as $attr) {
            if (isset($attr->getArguments()["Type"])) {
                return $attr->getArguments()["Type"];
            }
        }

        switch ($baseType) {
            case "string":
                return "VARCHAR(255)" . ($isNullable ? "" : " NOT NULL");
            case "float":
                return "DECIMAL(8,2)" . ($isNullable ? "" : " NOT NULL");
            case "DateTime":
            case "bool":
            case "int":
                return strtoupper($baseType) . ($isNullable ? "" : " NOT NULL");;
        }
    }

    /// string complet création de table depuis une classe donnée
    public static function getTableDefForClass(string $class): array
    {
        $table_defs = array_map(fn (ReflectionProperty $prop): string => " " . $prop->getName() . " " . self::getSQLTypeDefForField($prop), self::enumerateTableFields($class));
        return [implode(",", $table_defs), self::get_table_deps_recusrive($class, array())];
    }

    /// retourne les couples attributs/valeurs de chaque instance d'objet
    public static function getObjectValues(mixed $instanceObject): array
    {
        $reflectionObject = new ReflectionObject($instanceObject);
        $properties = $reflectionObject->getProperties();

        $values = array();
        $attributes = self::getObjectAttributes($instanceObject);

        foreach ($properties as $property) {
            $property->setAccessible(true);
            if (isset($attributes[$property->getName()])) {
                if ($attributes[$property->getName()]["AutoIncrement"]) {
                    $values[$property->getName()] = NULL;
                }
                if ($attributes[$property->getName()]["Ignore"]) {
                    continue;
                } else {
                    $value = $property->getValue($instanceObject);
                    if ($value instanceof DateTime)
                    {
                        $value = $value->format("Y-m-d H:i:s");
                    }
                    $values[$property->getName()] = $value;
                }
            } else {
                $value = $property->getValue($instanceObject);
                if ($value instanceof DateTime)
                {
                    $value = $value->format("Y-m-d H:i:s");
                }
                $values[$property->getName()] = $value;
            }
        }
        return $values;
    }

    /// retourne les attributs en fonction de TableOpt
    public static function getObjectAttributes(mixed $instanceObject): array
    {
        $reflectionObject = new ReflectionObject($instanceObject);
        $properties = $reflectionObject->getProperties();

        $attributesObject = array();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $attributes = $property->getAttributes(TableOpt::class);
            if (!empty($attributes)) {
                $propertyAttributes = array();
                foreach ($attributes as $attr) {
                    $arguments = $attr->getArguments();
                    foreach ($arguments as $cle => $valeur) {
                        $propertyAttributes[$cle] = $valeur;
                    }
                }
                $attributesObject[$property->getName()] = $propertyAttributes;
            }
        }
        return $attributesObject;
    }

    /// string pour requete sql en fonction du type
    public static function getStringValue(mixed $obj): string
    {
        if (is_null($obj)) {
            return "NULL";
        }

        switch (gettype($obj)) {
            case "string":
                return "'" . $obj . "'";
            case "object":
                if ($obj instanceof DateTime) {
                    return "'" . $obj->format("Y-m-d H:i:s") . "'";
                }
            default:
                return strval($obj);
        }
    }

    /// Retourne la requête SQL pour insérer un objet dans une table.
    public static function getInsertionString(mixed $obj, string $tableName): string
    {
        $fields = self::getObjectValues($obj);
        $names = array();
        $values = array();
        foreach ($fields as $name => $value) {
            array_push($names, $name);
            array_push($values, $value);
        }

        $fieldsStr = "(" . implode(", ", $names) . ")";

        $values = array_map(fn ($value) => ClassQL::getStringValue($value ?? null), $values);

        $valStr = "(" . implode(", ", $values) . ")";

        $sql = "INSERT INTO `" . $tableName . "` " . $fieldsStr . " VALUES " . $valStr . ";";
        return $sql;
    }


    /// Retourne la requête SQL pour mettre à jour un objet dans une table.
    public static function getUpdateString(DatabaseTable $obj): ?string
    {
        $champs = self::getObjectValues($obj);
        $PrimaryKeyName = self::get_table_primary_key($obj::class);
        $prop = new ReflectionProperty($obj::class, $PrimaryKeyName);
        $prop->setAccessible(true);
        $PrimaryKeyValue = $prop->getValue($obj);

        $modifs = array();
        foreach ($champs as $champ => $value) {
            if ($champ !== $PrimaryKeyName) {
                if ($value === null) {
                    $value = "NULL";
                }
                if ($value instanceof DateTime) {
                    $value = $value->format("Y-m-d H:i:s");
                }
                array_push($modifs, "`$champ` = '$value'");
            }
        }

        $modifsStr = implode(", ", $modifs);
        $tableName = $obj::TABLE_NAME;

        $sql = "UPDATE `" . $tableName . "` SET " . $modifsStr . " WHERE `" . $tableName . "`.`" . $PrimaryKeyName . "` = " . $PrimaryKeyValue . ";";
        return $sql;
    }

    // Retourne la requête pour enlever une ligne d'une table dans la bbd
    // FIXME : ne prend pas en compte les dependances des foreign key !
    public static function getDeleteString(DatabaseTable $obj): ?string
    {
        $tableName = $obj::TABLE_NAME;
        $PrimaryKeyName = self::get_table_primary_key($obj::class);
        $prop = new ReflectionProperty($obj::class, $PrimaryKeyName);
        $prop->setAccessible(true);
        $PrimaryKeyValue = $prop->getValue($obj);
        $sql = "DELETE FROM`" . $tableName . "` WHERE `" . $tableName . "`.`" . $PrimaryKeyName . "` = " . $PrimaryKeyValue . ";";
        echo $sql;
        return $sql;
    }

    /// Crée un objet à partir d'un tableau associatif et du nom de la classe de la table.
    public static function createFromFields(array $fieldAssoc, string $tableType): mixed
    {
        $class = new ReflectionClass($tableType);
        $obj = $class->newInstanceWithoutConstructor();

        foreach ($fieldAssoc as $key => $value) {
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
            }
        }

        return $obj;
    }

    /// Assure les singletons dans la bdd au niveau des attributs Unique
    public static function ensureUniqueIndex(DatabaseController $db, DatabaseTable $object): bool
    {
        $tableName = $object::TABLE_NAME;
        foreach (classQL::getObjectAttributes($object) as $cle => $attributs) {
            if (isset($attributs['Unique']) && $attributs['Unique']) {
                $valueUnique = classQL::getObjectValues($object)[$cle];
                $sql = "SELECT COUNT(*) as total FROM $tableName WHERE $cle = '$valueUnique'";

                $stmt = $db->getPDO()->prepare($sql);
                $stmt->execute();
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                if ($resultat > 0) {
                    return false;
                }
            }
        }
        return true;
    }

    /// Assure les singletons dans la bdd au niveau de la ligne complète de la table pour insert et modify
    public static function ensureUnique(DatabaseController $db, DatabaseTable $object, bool $allowsNull): bool
    {
        $tableName = $object::TABLE_NAME;
        if (self::ensureUniqueIndex($db,$object)){
            $obj = self::getObjectValues($object);
            $primaryKey = self::get_table_primary_key($object::TABLE_TYPE);
            $conds = array_map(function($field) use ($obj, $primaryKey, $allowsNull) {
                $cond1 = $field->name !== $primaryKey;
                $cond2 = isset($obj[$field->name]);
                $cond3 = ($allowsNull === true) ? "" : $field->getType()->allowsNull();
                if($cond1 && $cond2 && !$cond3){
                    if (!$obj[$field->name] instanceof DateTime){
                        $value = $obj[$field->name];
                    }
                    return $field->name . " = " . "'$value'";
                }
            }, self::enumerateTableFields($object::TABLE_TYPE));            
            $conds = array_filter($conds);
            $conds = implode(" AND ", $conds);

            $sql = "SELECT COUNT(*) as total FROM $tableName WHERE $conds";
            $stmt = $db->getPDO()->prepare($sql);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            if ($resultat > 0) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }
}
