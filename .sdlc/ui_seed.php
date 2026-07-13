<?php
// Seed for MAIN_HIDE_INACTIVE_USERS scenario: enables the option and ensures
// at least one disabled user exists so the Users list before/after screenshots
// visibly differ (disabled user row present vs hidden).
require dirname(__FILE__).'/../htdocs/master.inc.php';

$user->getrights();

require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$login = 'qaseed_disableduser';

$seeduser = new User($db);
$res = $seeduser->fetch(0, $login);
if ($res <= 0) {
	$seeduser = new User($db);
	$seeduser->login = $login;
	$seeduser->lastname = 'QASeed Disabled User';
	$seeduser->admin = 0;
	$seeduser->pass = 'QaSeedPass_'.uniqid();
	$newid = $seeduser->create($user);
	if ($newid <= 0) {
		echo "SEED_ERROR: failed to create seed user: ".implode(', ', $seeduser->errors)."\n";
		exit(1);
	}
}

if ($seeduser->statut != 0) {
	$seeduser->setStatut(0);
}

dolibarr_set_const($db, 'MAIN_HIDE_INACTIVE_USERS', '1', 'chaine', 0, '', $conf->entity);

echo "SEED_START_PATH=/user/list.php\n";
