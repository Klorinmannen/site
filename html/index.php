<?php
require_once('../src/init.php');

\site\page::default();

$page = page::get();
include($page);

