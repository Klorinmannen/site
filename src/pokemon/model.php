<?php
namespace pokemon;

class model
{
    public static function get_all(array $fields = [])
    {
        $table = table('Pokemon');
        $table->set_where_fields(['Active' => -1]);
        return $table->select($fields);
    }

    public static function get_by_id(int $id, array $fields = [])
    {
        $table = table('Pokemon');
        $table->set_where_fields(['PokemonID' => $id, 'Active' => -1]);
        return $table->select($fields);
    }

    public static function get_by_name(string $name, array $fields = [])
    {
        $table = table('Pokemon');
        $table->set_where_fields(['Name' => $name, 'Active' => -1]);
        return $table->select($fields);
    }
}
