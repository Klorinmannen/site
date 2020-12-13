<?php
namespace util\make;

class html
{
    public static function get_fields($opts)
    {
        if (isset($opts['id']))
           $fields['id'] = sprintf('id="%s"', $opts['id']);
        if (isset($opts['class']))
            $fields['class'] = sprintf('class="%s"', $opts['class']);
        if (isset($opts['style']))
            $fields['style'] = sprintf('style="%s"', $opts['style']);
        if (isset($opts['name']))
            $fields['name'] = sprintf('name="%s"', $opts['name']);
        if (isset($opts['value']))
            $fields['value'] = sprintf('value="%s"', $opts['value']);
        if (isset($opts['js_function'])) 
            $fields['js_function'] = $opts['js_function'];
        if (isset($opts['type']))
            $fields['type'] = sprintf('type="%s"', $opts['type']);
        if (isset($opts['required']))
            $fields['required'] = 'required';
        if (isset($opts['disabled']))
            $fields['disabled'] = 'disabled';
        if (isset($opts['checked']))
            $fields['checked'] = 'checked';

        return $fields;
    }
    
    public static function button($opts = [])
    {
        if (!$opts)
            return '';
        if (! isset($opts['title']))
            return '';
        
        $title = $opts['title'];
        unset($opts['title']);       

        $fields = static::get_fields($opts);

        $button = '<button %s>%s</button>';
        return sprintf($button, implode(' ', $fields), $title);
    }        

    public static function input($opts = [])
    {
        if (!$opts)
            return '';

        $fields = static::get_fields($opts);

        $input = '<input %s />';
        return sprintf($input, implode(' ', $fields));
    }
   
    public static function select($opts = [])
    {
        $html_default = '<select>Default select element</select>';

        if (!$opts)
            return $html_default;
        if (! isset($opts['select']))
            return $html_default;
        if (! isset($opts['options']))
            return $html_default;

        $options = [];
        if (\util\functions::validate_array($opts['options']))
            $options = $opts['options'];
                 
        $html_options = [];        
        if (isset($opts['default']))
            $html_options[] = sprintf('<option %s %s>%s</option>',
                                      'value="0"', 'name="default"', '-');

        foreach ($options as $option) {
            $title = '';
            if (isset($option['title']))
                $title = $option['title'];
            
            $option_tags = static::get_fields($option);            
            $html_options[] = sprintf('<option %s>%s</option>',
                                      implode(' ', $option_tags), $title);
        }

        $select_tags = static::get_fields($opts['select']);

        $html = '<select %s >%s</select>';        
        return sprintf($html,
                       implode(' ', $select_tags),
                       implode(' ', $html_options));
    }

    public static function a_href($opts)
    {
        $title = '';
        if (isset($opts['title']))
            $title = $opts['title'];

        $href = '';
        if (isset($opts['href']))
            $href = sprintf('href="%s"',
                            $opts['href']);

        $class = '';
        if (isset($opts['class']))
            $class = sprintf('class="%s"',
                             $opts['class']);

        return sprintf('<a %s %s >%s</a>',
                       $class,
                       $href,
                       $title);       
    }
    
    public static function modal()
    {
        $modal = '<div id="modal" class="modal" style="display:none;">';
        $modal .= '<div id="modal_background" class="modal_background"></div>';
        $modal .= '<div id="modal_content" class="modal_content"></div>';
        $modal .= '</div>';

        return $modal;
    }
}
