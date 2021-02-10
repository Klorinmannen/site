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
        $pokemon_data = $this->_model->get_by_id($id, self::get_get_fields());
        return self::prepare_response($pokemon_data);
    }
        
    public function get_list()
    {
        $pokemon_data = $this->_model->get_list(self::get_get_fields());        
        return self::prepare_response($pokemon_data);
    }

    public function get_shiny_list()
    {
        $pokemon_data = $this->_model->get_shiny_list(self::get_get_fields());        
        return self::prepare_response($pokemon_data);
    }

    public function update()
    {
        $data = $this->_data;

        if (!$pokemon_id = array_validate_key_id('pokemon_id', $data))
            throw new \Exception('Missing/malformed pokemon id', 400);

        $new_data = [];
        foreach (static::GET_FIELDS as $db_field => $code_field)
            if (isset($data[$code_field]))
                $new_data[$db_field] = $data[$code_field];

        if (!$new_data)
            return [];        
        if (!$rows_affected = $this->_model->update($pokemon_id, $new_data))
            return [];

        $pokemon = $this->model->get_by_id($pokemon_id, self::get_get_fields());        
        return self::prepare_response($pokemon);
    }

    public function insert()
    {
        $data = $this->_data;
        
        if (!$pokemon_id = array_validate_key_id('pokemon_id', $data))
            throw new \Exception('Missing/malformed pokemon id', 400);

        if ($pokemon_exists = $this->_model->get_by_id($pokemon_id, self::get_get_fields()))
            throw new \Exception('Pokemon is already in database, did you mean to patch?', 400);
        
        if (!$name = array_validate_key_string('name', $data))
            throw new \Exception('Missing/malformed name', 400);

        $new_data = [];
        if ($dex_nbr = array_validate_key_string('dex_number', $data)) {
            if (!match_pattern('/^#\d{3}/', $dex_nbr))
                throw new \Exception("Malformed dex number: $dex_nbr, valid: #NNN", 400);
        } else {
            if ($pokemon_id >= 100)
                $new_data['DexID'] => sprintf('#%d', $pokemon_id);
            else
                $new_data['DexID'] => sprintf('#%03d', $pokemon_id);
        }
                
        foreach (static::GET_FIELDS as $db_field => $code_field)
            if (isset($data[$code_field]))
                $new_data[$db_field] = $data[$code_field];

        if (!$new_data)
            return [];        
        if (!$rows_affected = $this->_model->add_new($new_data))
            return [];

        $pokemon = $this->model->get_by_id($pokemon_id, self::get_get_fields());        
        return self::prepare_response($pokemon);
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
