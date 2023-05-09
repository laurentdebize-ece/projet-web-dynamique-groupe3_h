<?php
require_once 'src/database/models/models.php';
require_once 'src/database/ClassQL.php';

// echo ClassQL::getTableDefForClass(Evaluation::class);
// var_dump(ClassQL::getTableDefForClass(Evaluation::class));

$db = DatabaseController::getInstance();
$db->initTables();
$ecole = new Ecole("ECE", 'Engineering');
$ecolea = new Ecole("ESILV", 'Engineering');
Ecole::insert($db, $ecolea);
var_dump(ClassQL::createFromFields(Ecole::select($db, null)[4], Ecole::class));