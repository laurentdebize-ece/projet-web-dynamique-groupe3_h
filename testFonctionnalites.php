<?php
require_once 'src/database/models/models.php';
require_once 'src/database/ClassQL.php';

$db = DatabaseController::getInstance();
$db->initTables();
$db->initDefaultValues();
var_dump(Competence::getCompetencesByMatiere($db,1));
Competence::addCompetenceUser($db,3,"Gauss",["Electromagnétisme"],["Monde du travail"]);
var_dump(Matiere::getAllSubjectsUsers($db,11));
var_dump(ThemesCompetences::getSubjectCompetencesThemeUser($db,3));

// $usr = new User(User::ACCOUNT_TYPE_ADMIN, "admin@localhost", "Admin", "Admin", password_hash("admin", PASSWORD_BCRYPT));
// User::insert($db, $usr);