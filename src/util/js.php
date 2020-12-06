<?php
namespace util;

class js
{
    public static function include($script_path = '')
    {
        if (!$script_path)
            return '';
        
        $script = file_get_contents($script_path);
        if($script === false)
            throw new \Exception('Failed to get js: '.$script_path);

        return sprintf('<script>%s</script>', $script);
    }
}
