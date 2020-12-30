<?php
namespace pokemon\api;

class controller
{
    public const GET_FIELDS = [ 'PokemonID' => 'pokemon_id',
                                'ParentPokemonIDList' => 'parent_pokoemon_id_list',
                                'DexID' => 'dex_number',
                                'Name' => 'name',
                                'Attack' => 'attack',
                                'Defense' => 'defense',
                                'Stamina' => 'stamina',
                                'Shiny' => 'shiny',
                                'Shadow' => 'shadow' ];

    public static function get_get_fields()
    {
        return array_keys(static::GET_FIELDS);
    }
    
    public static function get_by_id($id)
    {
        if (!validate_id($id))
            throw new \Exception('Invalid pokemon id', 400);
        $pokemon_data = \pokemon\model::get_by_id($id, static::get_get_fields());
        return static::prepare_response($pokemon_data);
    }

    public static function get_by_name($name)
    {
        $sanitized_name = sanitize_string($name);
        $pokemon_data = \pokemon\model::get_by_name($sanitized_name, static::get_get_fields());
        return static::prepare_response($pokemon_data);
    }
        
    public static function get_list()
    {
        $pokemon_data = \pokemon\model::get_list(static::get_get_fields());        
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

        return \util\json::encode($response_data);
    }

    public static function format_response($pokemon)
    {
        $response = [];
        foreach (static::GET_FIELDS as $db_field => $value_field)
            $response[$value_field] = $pokemon[$db_field];
        return $response;
    }
}
