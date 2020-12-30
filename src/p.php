<?php
require_once('/var/www/site/src/init.php');

error_reporting(-1);

if (true)
{

    $result = \pokemon\api\controller::get_by_id(3);
    var_dump($result);
}
