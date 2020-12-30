<?php
namespace pokemon;

class model
{
    public static function get_list(array $fields = [])
    {
        return table('Pokemon')->select($fields)->query();
    }

    public static function get_by_id(int $id, array $fields = [])
    {
        return table('Pokemon')->select($fields)->where(['PokemonID' => $id])->query();
    }

    public static function get_by_name(string $name, array $fields = [])
    {
        return table('Pokemon')->select($fields)->where(['Name' => $name])->query();
    }

    public static function insert(array $pokemon = [])
    {
        return table('Pokemon')->insert($pokemon)->query();
    }

    public static function update_by_id(int $pokemon_id, array $pokemon = [])
    {
        return table('Pokemon')->update($pokemon)->where(['PokemonID' => $pokemon_id])->query();
    }
}
