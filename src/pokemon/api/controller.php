<?php
namespace pokemon\api;

class controller
{
    public const EMPTY_RESPONSE = 'Not found';

    public const GET_FIELDS = [ 'PokemonID' => 'pokemon_id',
                                'ParentPokemonIDList' => 'parent_pokemon_id_list',
                                'DexID' => 'dex_id',
                                'Name' => 'name',
                                'Attack' => 'attack',
                                'Defense' => 'defense',
                                'Stamina' => 'stamina',
                                'Shiny' => ['shiny', 'flag'],
                                'Shadow' => ['shadow', 'flag'] ];
        
    public static function get_by_id($id)
    {
        if (!validate_id($id))
            throw new \Exception('bad request, invalid pokemon id', 400);
        $pokemon_data = \pokemon\model::get_by_id($id);
        return static::prepare_response($pokemon_data);
    }

    public static function get_by_name($name)
    {
        $sanitized_name = sanitize_string($name);
        $pokemon_data = \pokemon\model::get_by_name($sanitized_name);
        return static::prepare_response($pokemon_data);
    }
        
    public static function get_list()
    {
        $pokemon_data = \pokemon\model::get_list();        
        return static::prepare_response($pokemon_data);
    }

    public static function prepare_response($pokemon_data)
    {
        if (!$pokemon_data)
            throw new \Exception(static::EMPTY_RESPONSE, 404);

        $response = [];
        if (isset($pokemon_data[0]))
            foreach ($pokemon_data as $pokemon)
                $response[] = static::format_repsonse($pokemon);
        else
            $response = static::format_response($pokemon_data);

        return \util\json::encode($response);
    }

    public static function format_response($pokemon)
    {
        $response_object = [];
        foreach (static::GET_FIELDS as $db_field => $value_field) {

            $field = $value_field;
            $value = $pokemon[$db_field];
            if (is_array($value_field)) {
                switch ($value_field[1]) {
                case 'flag':
                    $field = $value_field[0];
                    $value = $value == -1 ? 'yes' : 'no';
                    break;
                default:
                    throw new \Exception('Unknown field type', 500);
                    break;
                }
            }
            $response_object[$field] = $value;
        }
        return $response_object;
    }
}
