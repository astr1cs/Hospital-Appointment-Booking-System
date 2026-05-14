<?php
// includes/init.php - Load this in ALL your pages

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

// Start session automatically
SessionManager::start();
?>