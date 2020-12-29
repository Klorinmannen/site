<?php
namespace pokemon;

class model
{
    public static function get_list()
    {
        return table('Pokemon')->select()->query();
    }

    public static function get_by_id(int $id)
    {
        return table('Pokemon')->select()->where(['PokemonID' => $id])->query();
    }

    public static function get_by_name(string $name)
    {
        return table('Pokemon')->select()->where(['Name' => $name])->query();
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
