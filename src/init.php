<?php
// auto_loader
require_once('util/auto_loader.php');

// Functions
require_once('functions.php');

/*
  initiate globals 
*/

\util\pdo::init();
\user\action::init();
