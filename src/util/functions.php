<?php
namespace util;

const ID_PATTERN = '/^[0-9]+$/';
const INT_PATTERN = '/^-[0-9]+$|^[0-9]+$/';
const STRING_PATTERN = '/^[\w]+$/';

function validate_id($id)
{
    return (preg_match(ID_PATTERN, $id) === 1);
}

function validate_int($int)
{
    return (preg_match(INT_PATTERN, $int) === 1);
}

function validate_string($string)
{
    return (preg_match(STRING_PATTERN, $string) === 1);
}
 
function validate_array($subject)
{
    return is_array($subject);
}

function validate_array_key_array($container, $subject)
{
    if (!$set_subject = is_set($container, $subject))
        return [];
    if (!validate_array($set_subject))
        return [];
    return $set_subject;        
}
    
function validate_array_key_id($container, $subject)
{
    if (!$set_subject = is_set($container, $subject))
        return 0;
    if (!validate_id($set_subject))
        return 0;        
    return $set_subject;
}

function validate_array_key_int($container, $subject)
{
    if (!$set_subject = is_set($container, $subject))
        return 0;
    if (!validate_int($set_subject))
        return 0;        
    return $set_subject;
}

function validate_array_key_string($container, $subject)
{
    if (!$set_subject = is_set($container, $subject))
        return '';
    if (!validate_string($set_subject))
        return '';        
    return $set_subject;
}    

function validate_int_input($int, $default_return = 0)
{
    if (! isset($default_return['default']))
        $default_return['default'] = 0;

    if (! isset($_REQUEST[$int]))
        return $default_return['default'];

    $int = $_REQUEST[$int];
    if (!validate_int($int))
        return $default_return['default'];

    return $int;
}
    
function validate_id_input($id, $default_return = 0)
{
    if (! isset($default_return['default']) )
        $default_return['default'] = 0;

    if (! isset($_REQUEST[$id]) )
        return $default_return['default'];

    $id = $_REQUEST[$id];
    if (!validate_id($id) )
        return $default_return['default'];

    return $id;
}

function validate_string_input($string, $default_return = '')
{
    if (! isset($default_return['default']) )
        $default_return['default'] = '';

    if (! isset($_REQUEST[$string]) )
        return $default_return['default'];

    $string = $_REQUEST[$string];
    if (!validate_string($string) )
        return $default_return['default'];

    return $string;
}
    
function validate_array_input($string, $default_return = [])
{
    if (! isset($default_return['default']) )
        $default_return['default'] = [];

    if (! isset($_REQUEST[$string]) )
        return $default_return['default'];

    $subject = $_REQUEST[$string];
    if (!validate_array($subject) )
        return $default_return['default'];

    return $subject;
}

// Helper method
function is_set($container, $subject)
{
    if (!$subject)
        return false;
    if (!$container)
        return false;
    if (! isset($container[$subject]))
        return false;
    return $container[$subject];
}

function pdo()
{
    return $GLOBALS['pdo'];
}
