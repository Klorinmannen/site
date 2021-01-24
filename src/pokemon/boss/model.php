<?php
namespace pokemon\boss;

class model
{
    public static function get_list(array $fields = [])
    {
        return table('PokemonBoss')->select($fields)->where('PokemonBoss.Active <> 0')->join(['Pokemon',
                                                                                              'PokemonForm',
                                                                                              'PokemonBossTier'], 'INNER JOIN')->on(['PokemonID',
                                                                                                                                     'PokemonFormID',
                                                                                                                                     'PokemonBossTierID'])->query();
    }

    public static function get_shiny(array $fields = [])
    {
        return table('PokemonBoss')->select($fields)->where('Pokemon.Shiny <> 0 AND PokemonBoss.Active <> 0')->join(['Pokemon',
                                                                                                                     'PokemonForm',
                                                                                                                     'PokemonBossTier'], 'INNER JOIN')->on(['PokemonID',
                                                                                                                                                            'PokemonFormID',
                                                                                                                                                            'PokemonBossTierID'])->query();
    }                                                    
}
