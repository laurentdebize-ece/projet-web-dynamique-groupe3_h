<?
require 'src/models.php';


$refl = new ReflectionClass(Theme::class);
$props = $refl->getProperties();
foreach ($props as $prop) {
    echo $prop->getName() . "\n";
}