<?php
namespace pokemon;

class api extends \api\request
{
    public const FIELDS = [ 'PokemonID',
                            'ParentPokemonIDList',
                            'DexID',
                            'Name',
                            'Attack',
                            'Defense',
                            'Stamina',
                            'Shiny',
                            'Shadow' ];    

    public static function get($pokemon_id)
    {
        if (!validate_id($pokemon_id))
            throw new \Exception('bad request, invalid pokemon id', 400);

        $table = table('Pokemon');
        $table->set_where_fields(['PokemonID' => $pokemon_id]);
        echo json_encode($table->select(static::FIELDS));
    }

    public static function get_list()
    {        
        echo json_encode(table('Pokemon')->select(static::FIELDS));
    }
}
