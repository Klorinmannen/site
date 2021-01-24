<?php

function table($name)
{
    return new \util\table($name);
}

function svg_pie(array $data = [])
{
    return new \make\svg\pie($data);
}

function svg_donut(array $data = [])
{
    return new \make\svg\donut($data);
}

function pdo()
{
    return $GLOBALS['pdo'];
}

function user()
{
    return $_SESSION['user'];
}

function subject()
{
    return $GLOBALS['subject'];
}
