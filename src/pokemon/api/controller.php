<?php
namespace pokemon\api;

class controller extends \api\controller
{
    public const GET_FIELDS = [ 'PokemonID' => 'pokemon_id',
                                'DexID' => 'dex_number',
                                'Name' => 'name',
                                'Shiny' => 'shiny',
                                'Shadow' => 'shadow' ];

    public static function get_get_fields()
    {
        return array_keys(static::GET_FIELDS);
    }
    
    public static function get_by_id_by_name($string)
    {
        if (validate_id($string)) {
            $pokemon_data = \pokemon\model::get_by_id($string, static::get_get_fields());
        } else {
            $sanitized_name = sanitize_string($string);
            $pokemon_data = \pokemon\model::get_by_name($sanitized_name, static::get_get_fields());
        }
        return static::prepare_response($pokemon_data);
    }
        
    public static function get_list()
    {
        $pokemon_data = \pokemon\model::get_list(static::get_get_fields());        
        return static::prepare_response($pokemon_data);
    }

    public static function get_shiny_list()
    {
        $pokemon_data = \pokemon\model::get_shiny_list(static::get_get_fields());        
        return static::prepare_response($pokemon_data);
    }

    public static function prepare_response($pokemon_data)
    {
        if (!$pokemon_data)
            throw new \Exception('Pokemon(s) not found', 404);

        $response_data = [];
        if (isset($pokemon_data[0]))
            foreach ($pokemon_data as $pokemon)
                $response_data[] = static::format_response($pokemon);
        else
            $response_data = static::format_response($pokemon_data);

        return $response_data;
    }

    public static function format_response($pokemon)
    {
        $response = [];
        foreach (static::GET_FIELDS as $db_field => $value_field)
            $response[$value_field] = $pokemon[$db_field];
        return $response;
    }
}
