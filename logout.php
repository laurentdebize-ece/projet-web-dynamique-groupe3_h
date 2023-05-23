<?php

require_once 'src/session.php';

$sess = SessionManager::getInstance();
$sess->logout();

header("Location: /");
