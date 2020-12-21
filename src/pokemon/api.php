<?php
namespace pokemon;

class api
{
    public const API_ENDPOINTS = 'pokemon/endpoints.json';
    
    public const FIELDS = [ 'PokemonID',
                            'ParentPokemonIDList',
                            'DexID',
                            'Name',
                            'Attack',
                            'Defense',
                            'Stamina',
                            'Shiny',
                            'Shadow' ];    
    
    public static function get_endpoints()
    {
        $path = sprintf('%s%s', \site::SRC_DIR, static::API_ENDPOINTS);
        return \util\json::parse($path);
    }
    
    public static function get($pokemon_id)
    {
        if (!validate_id($pokemon_id))
            throw new \Exception('bad request, invalid pokemon id', 400);

        $table = table('Pokemon');
        $table->set_where_fields(['PokemonID' => $pokemon_id]);
        echo json_encode($table->select(static::FIELDS));
    }

    public static function get_family($id)
    {
        if (!validate_id($pokemon_id))
            throw new \Exception('bad request, invalid pokemon id', 400);

    }
    
    public static function get_list()
    {
        echo json_encode(table('Pokemon')->select(static::FIELDS));
    }   
}
