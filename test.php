<?php
require_once 'src/database/models/models.php';
require_once 'src/database/ClassQL.php';

$db = DatabaseController::getInstance();
$db->initTables();