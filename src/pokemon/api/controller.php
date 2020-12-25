<?php
namespace pokemon\api;

class controller
{
    public const ENDPOINTS = 'pokemon/api/endpoints.json';
    
    public const FIELDS = [ 'PokemonID',
                            'ParentPokemonIDList',
                            'DexID',
                            'Name',
                            'Attack',
                            'Defense',
                            'Stamina',
                            'Shiny',
                            'Shadow' ];    
        
    public static function get_by_id($id)
    {
        if (!validate_id($id))
            throw new \Exception('bad request, invalid pokemon id', 400);
        $data = \pokemon\model::get_by_id($id, static::FIELDS);
        return json_encode($data);
    }

    public static function get_by_name($name)
    {
        if (!sanitize_string($name))
            throw new \Exception('bad request, invalid pokemon id', 400);
        $data = \pokemon\model::get_by_name($name, static::FIELDS);
        return json_encode($data);
    }
    
    public static function get_family($id)
    {
        if (!validate_id($pokemon_id))
            throw new \Exception('bad request, invalid pokemon id', 400);
    }
    
    public static function get_list()
    {
        $data = \pokemon\model::get_all(static::FIELDS);
        return json_encode($data);
    }   
}
