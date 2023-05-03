<?php
require_once 'src/models.php';
require_once 'src/database/ClassQL.php';

// DatabaseController::getInstance()->createTable("fuck", ClassQL::getTableDefForClass(User::class));
// User::select(null);
// SELECT * FROM `Users`

// $user = new User("salut", "jamy", "lol", "sjidsijds",7,18);
// echo ClassQL::getTableDefForClass(User::class);
// User::select(null);

$db = DatabaseController::getInstance();
$user = new User("salut", "jamy","sjidsijds", 7, 18);
$db->createTable($user::TABLE_NAME,$user::TABLE_TYPE);

function getAttributesByNameObject(DatabaseTable $instanceObject): array
{
    $reflectionObject = new ReflectionObject($instanceObject);
    $properties = $reflectionObject->getProperties();

    $attributesObject = array();

    foreach ($properties as $property) {
        $property->setAccessible(true);
        $attributes = $property->getAttributes('TableOpt');
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

var_dump(getAttributesByNameObject($user));

function getValuesByNameObject(DatabaseTable $instanceObject): array
{
    $reflectionObject = new ReflectionObject($instanceObject);
    $properties = $reflectionObject->getProperties();

    $values = array();
    $attributes = getAttributesByNameObject($instanceObject);

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

$array = getAttributesByNameObject($user);



function insert(DatabaseController $db, DatabaseTable $object): void
{
    $values = getValuesByNameObject($object);
    var_dump($values);
    $attributes = getAttributesByNameObject($object);
    
    $objs = [];
    foreach ($values as $name => $value) {
        if (is_string($value)) {
            $objs[] = "'" . $value . "'";
        }
        else {
            $objs[] = $value;
        }
            
    }
    $stringObj = "(" . implode(",", $objs) . ")";
    
    try{
        $sql = "INSERT INTO `" . $object::TABLE_NAME . "` VALUES " . $stringObj . ";";
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
insert($db,$user);

// $array = classQl::enumerateTableFields(User::class);
// var_dump($array);
// $user = new User("salut", "jamy", "lol", "sjidsijds", 7, 18);

// $reflectionObject = new ReflectionObject($user);
// echo $reflectionObject;
// $property = $reflectionObject->getProperty('idUser');
// $property->setAccessible(true);
// $docComment = $property->getDocComment();
// echo $docComment;
// 
// $isAutoIncrement = strpos($docComment, 'AutoIncrement') !== false;
// 
// echo $isAutoIncrement ? 'La propriété idUser est en auto-incrément.' : 'La propriété idUser n\'est pas en auto-incrément.';

// Obtention de la réflexion de la propriété "username" de la classe User
// $reflectionProperty = new ReflectionProperty('User', 'idUser');

// Rendre la propriété accessible, même si elle est privée
// $reflectionProperty->setAccessible(true);
// $attributes = $reflectionProperty->getAttributes('TableOpt');
//foreach ($attributes as $attr) {
//    echo $attr->getArguments();
//}

// Obtention de la valeur de la propriété "username" de l'instance de la classe User
// $usernameValue = $reflectionProperty->getValue($user);

// echo $usernameValue; // affiche "johndoe"
// $objs = [0,"salut", "jamy", "lol", "sjidsijds",7,18];
// User::insert($db,$objs);
$user = new User("salut", "jamy", "lol", "sjidsijds", 7, 18);
if ($user instanceof DatabaseTable) {
    echo "L'objet \$user est une instance de la classe User.";
} else {
    echo "L'objet \$user n'est pas une instance de la classe User.";
}

// User::select(0);
// } catch (Exception $e) {
//     echo "error";
//     var_dump($e);
// }
// echo ClassQL::getTableDefForClass(User::class);