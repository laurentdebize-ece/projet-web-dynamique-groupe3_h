<?php

require 'src/session.php';

$sess = SessionManager::getInstance();
$sess->ensureLoggedIn();

switch ($sess->getUser()->getAccountType()) {
    case User::ACCOUNT_TYPE_USER:
        header('Location: userDashboard.php');
        break;

    case User::ACCOUNT_TYPE_ADMIN:
        header('Location: adminDashboard.php');
        break;

    case User::ACCOUNT_TYPE_PROF:
        header('Location: profDashboard.php');
        break;
}
