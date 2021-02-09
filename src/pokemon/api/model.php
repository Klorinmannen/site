<?php
namespace pokemon\api;

class model
{
    public $_table = null;
    
    public function __construct()
    {
        $this->_table = table('Pokemon');
    }

    public function get_list(array $fields = [])
    {
        return $this->_table->select($fields)->query();
    }

    public function get_shiny_list(array $fields = [])
    {
        return $this->_table->select($fields)->where('Shiny <> 0')->query();
    }
    
    public function get_by_id(int $id, array $fields = [])
    {
        return $this->_table->select($fields)->where(['PokemonID' => $id])->query();
    }

    public function get_by_name(string $name, array $fields = [])
    {
        return $this->_table->select($fields)->where(['Name' => $name])->query();
    }

    public function insert(array $pokemon = [])
    {
        return $this->_table->insert($pokemon)->query();
    }

    public function update_by_id(int $pokemon_id, array $pokemon = [])
    {
        return $this->_table->update($pokemon)->where(['PokemonID' => $pokemon_id])->query();
    }
}
