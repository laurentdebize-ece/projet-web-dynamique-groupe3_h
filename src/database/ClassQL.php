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
        public bool $PrimaryKey = false,
        public bool $ForeignKey = false,
        public ?string $Type = null,
    ) {
    }
}


final class ClassQL
{

    public static function getArrayValuesObject(DatabaseTable $instanceObject): array
    {
        $reflectionObject = new ReflectionObject($instanceObject);
        $properties = $reflectionObject->getProperties();

        $values = array();
        $attributes = self::getArrayAttributesObject($instanceObject);

        foreach ($properties as $property) {
            $property->setAccessible(true);
            if (isset($attributes[$property->getName()])){
                if ($attributes[$property->getName()]["AutoIncrement"]){
                    $values[$property->getName()] = 0;
                }
            }
            else{
                $value = $property->getValue($instanceObject);
                $values[$property->getName()] = $value;
            }
        }
        return $values;
    }

    public static function getArrayAttributesObject(DatabaseTable $instanceObject): array
    {
        $reflectionObject = new ReflectionObject($instanceObject);
        $properties = $reflectionObject->getProperties();

        $attributesObject = array();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $attributes = $property->getAttributes(TableOpt::class);
            if (!empty($attributes)){
                $propertyAttributes = array();
                foreach ($attributes as $attr) {
                    $arguments = $attr->getArguments();
                    foreach ($arguments as $cle => $valeur){
                        $propertyAttributes[$cle] = $valeur;
                    }
                }
                $attributesObject[$property->getName()] = $propertyAttributes;
            }
        }
        return $attributesObject;
    }

    private static function include_field_comparator(ReflectionProperty $prop): bool
    {
        $attributes = $prop->getAttributes('TableOpt');
        foreach ($attributes as $attr) {
            if ($attr->getArguments()["Ignore"]) {
                return false;
            }
        }
        return true;
    }

    /// Retourne tous les champs utilisables pour créer une table SQL.
    public static function enumerateTableFields(string $table): array
    {
        $refl = new ReflectionClass($table);
        $props = $refl->getProperties(ReflectionProperty::IS_PRIVATE);

        return array_filter($props, 'self::include_field_comparator');
    }


    ///TODO: transformer en template pour insertion dans la BDD.
    public static function getInsertionString(mixed $obj, string $tableName): string
    {
        $fields = ClassQL::enumerateTableFields(get_class($obj));
        array_walk($fields, fn (ReflectionProperty $prop) => $prop->setAccessible((true)));


        $fieldList = array_map(fn (ReflectionProperty $prop): string => $prop->getName(), $fields);
        $sqlStr = "(";
        $sqlStr .= implode(", ", $fieldList);
        $sqlStr .= ")";
        // echo $sqlStr;

        $values = array_map(fn (ReflectionProperty $prop) => ClassQL::getStringValue($prop->getValue($obj) ?? null), $fields);
        $valStr = "(";
        $valStr .= implode(", ", $values);
        $valStr .= ")";

        // echo $valStr;

        $sql = "INSERT INTO `" . $tableName . "` " . $sqlStr . " VALUES " . $valStr . ";";
        return $sql;
    }

    public static function getStringValue(mixed $obj): string {
        if (is_null($obj)) {
            return "NULL";
        }

        switch (gettype($obj)) {
            case "string":
                return "'" . $obj . "'";
            case "DateTime":
                return "'" . $obj->format("Y-m-d H:i:s") . "'";
            default:
                return strval($obj);
        }
    }

    public static function getTableDefForClass(string $class): string
    {
        $table_fields = array_map(fn (ReflectionProperty $prop): string => " " . $prop->getName() . " " . self::getSQLTypeDefForField($prop), self::enumerateTableFields($class));
        return implode(",", $table_fields);
    }

    private static function getSQLTypeDefForField(ReflectionProperty $prop): string
    {
        $type = self::getSQLTypeForField($prop);

        $attributes = $prop->getAttributes('TableOpt');
        foreach ($attributes as $attr) {

            if ($attr->getArguments()["PrimaryKey"]) {
                $type .= " PRIMARY KEY";
            }

            if ($attr->getArguments()["AutoIncrement"]) {
                $type .= " AUTO_INCREMENT";
            }
        }

        return $type;
    }

    public static function getSQLTypeForField(ReflectionProperty $prop): string
    {
        $baseType = $prop->getType()->getName();
        $isNullable = $prop->getType()->allowsNull();

        // on verifie que on n'a pas d'override de type spécifié sur la table.
        $attributes = $prop->getAttributes('TableOpt');
        foreach ($attributes as $attr) {
            if (isset($attr->getArguments()["Type"])) {
                return $attr->getArguments()["Type"];
            }
        }

        switch ($baseType) {
            case "string":
                return "VARCHAR(255)" . ($isNullable ? "" : " NOT NULL");
            case "DateTime":
            case "bool":
            case "int":
                return strtoupper($baseType) . ($isNullable ? "" : " NOT NULL");;
        }
    }
}
