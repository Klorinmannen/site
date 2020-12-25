<?php
// auto_loader
require_once('util/auto_loader.php');

// Start session after auto_loader
session_start();

// General functions
require_once('functions.php');
require_once('objects.php');

// Initiate globals / sessions 
\util\pdo::init();
\user\controller::init();
