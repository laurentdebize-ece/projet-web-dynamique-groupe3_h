<?php
require_once 'src/models.php';
require_once 'src/database/ClassQL.php';

// DatabaseController::getInstance()->createTable("fuck", ClassQL::getTableDefForClass(User::class));
// User::select(null);
// SELECT * FROM `Users`

$user = new User("salut@ccool.fr", "Dawood", "Jerry", "sjidsijds", 69, 1);
User::insert($user);