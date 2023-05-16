<?php
require_once 'src/session.php';

$sess = SessionManager::getInstance();
$sess->ensureLoggedIn();

echo "Authentifié en tant que " . $sess->getUser()->getDisplayName();