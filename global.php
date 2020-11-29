<?php

new \util\database();
function database()
{
    return $GLOBALS['database'];
}

new \util\pdo();
function pdo()
{
    return $GLOBALS['pdo']->get_pdo();
}


