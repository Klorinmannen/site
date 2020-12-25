<?php
namespace pokemon;

class model
{
    public static function get_all(array $fields = [])
    {
        return table('Pokemon')->select()->where(['Active' => -1])->query();
    }

    public static function get_by_id(int $id, array $fields = [])
    {
        return table('Pokemon')->select($fields)->where(['PokemonID' => $id, 'Active' => -1])->query();
    }

    public static function get_by_name(string $name, array $fields = [])
    {
        return table('Pokemon')->select($fields)->where(['Name' => $name, 'Active' => -1])->query();
    }
}
