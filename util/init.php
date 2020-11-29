<?php

$GLOBALS['pdo_class'] = new \util\pdo();

function pdo()
{
    return $GLOBALS['pdo_class']->get_pdo();
}

