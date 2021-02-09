<?php
namespace pokemon\api;

class controller extends \api\controller
{
    public const GET_FIELDS = [ 'PokemonID' => 'pokemon_id',
                                'DexID' => 'dex_number',
                                'Name' => 'name',
                                'Shiny' => 'shiny',
                                'Shadow' => 'shadow' ];

    public function get_get_fields()
    {
        return array_keys(static::GET_FIELDS);
    }
    
    public function get_by_id($id)
    {
        if (!validate_id($id))
            throw new \Exception('Missing/malformed id', 400);
        $pokemon_data = $this->_model->get_by_id($id, static::get_get_fields());
        return self::prepare_response($pokemon_data);
    }
        
    public function get_list()
    {
        $pokemon_data = $this->_model->get_list(static::get_get_fields());        
        return self::prepare_response($pokemon_data);
    }

    public function get_shiny_list()
    {
        $pokemon_data = $this->_model->get_shiny_list(static::get_get_fields());        
        return self::prepare_response($pokemon_data);
    }

    public function prepare_response($pokemon_data)
    {
        if (!$pokemon_data)
            throw new \Exception('Pokemon(s) not found', 404);

        $response_data = [];
        if (isset($pokemon_data[0]))
            foreach ($pokemon_data as $pokemon)
                $response_data[] = self::format_response($pokemon);
        else
            $response_data = self::format_response($pokemon_data);

        return $response_data;
    }

    public function format_response($pokemon)
    {
        $response = [];
        foreach (static::GET_FIELDS as $db_field => $value_field)
            $response[$value_field] = $pokemon[$db_field];
        return $response;
    }
}
