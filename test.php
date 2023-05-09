<?php
require_once 'src/database/models/models.php';
require_once 'src/database/ClassQL.php';

// echo ClassQL::getTableDefForClass(Evaluation::class);
// var_dump(ClassQL::getTableDefForClass(Evaluation::class));

$db = DatabaseController::getInstance();
$db->createAllTable();
$user = new Evaluation("lol", 0, 0, 0);
Evaluation::insert($db, $user);
var_dump(Evaluation::fromFields(Evaluation::select($db, null)[0]));
// var_dump(User::fromFields(User::select($db, null)[0]));