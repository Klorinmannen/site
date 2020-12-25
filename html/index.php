<?php
require_once('../src/init.php');

\site\view::default();

$page = \page\controller::get_page();
include($page);

