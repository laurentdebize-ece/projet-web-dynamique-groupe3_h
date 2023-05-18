<?php
require_once 'src/database/models/models.php';
require_once 'src/database/ClassQL.php';

$db = DatabaseController::getInstance();
$db->initTables();
$db->initDefaultValues();

// $usr = new User(User::ACCOUNT_TYPE_ADMIN, "admin@localhost", "Admin", "Admin", password_hash("admin", PASSWORD_BCRYPT));
// User::insert($db, $usr);