<?php
namespace pokemon\boss\api;

class controller extends \api\controller
{
    public const GET_FIELDS = [ 'PokemonBoss.PokemonID' => 'pokemon_id',
                                'PokemonForm.Form' => 'pokemon_form',
                                'PokemonBossTier.Name AS TierName' => 'pokemon_tier_name',
                                'PokemonBossTier.Tier' => 'pokemon_tier',
                                'MinCP' => 'normal_min_cp',
                                'MaxCP' => 'normal_max_cp',
                                'BoostedMinCP' => 'boosted_min_cp',
                                'BoostedMaxCP' => 'boosted_max_cp',
                                'Pokemon.Name' => 'pokemon_name',
                                'Pokemon.DexID' => 'pokemon_dex_number' ];

    public const REPLACE_FIELDS = [ '/^[\w]+\.[\w]+\s*AS\s*|[\w]+\./' => ''];
    
    public static function get_get_fields()
    {
        return array_keys(static::GET_FIELDS);
    }
            
    public static function get_boss_list()
    {
        $pokemon_data = \pokemon\boss\model::get_list(self::get_get_fields());        
        return static::prepare_response($pokemon_data);
    }

    public static function get_boss_shiny_list()
    {
        $boss_data = \pokemon\boss\model::get_shiny(self::get_get_fields());        
        return static::prepare_response($boss_data);
    }
    
    public static function prepare_response($data)
    {
        if (!$data)
            throw new \Exception('No pokemon boss found', 404);
        
        $response_data = [];
        if (isset($data[0]))
            foreach ($data as $pokemon)
                $response_data[] = static::format_response($pokemon);
        else
            $response_data = static::format_response($data);

        return $response_data;
    }

    public static function format_response($pokemon)
    {
        $response = [];
        $pattern = key(static::REPLACE_FIELDS);
        $replace_with = current(static::REPLACE_FIELDS);            
        foreach (static::GET_FIELDS as $db_field => $value_field) {
            $db_field = preg_replace($pattern, $replace_with, $db_field);
            $response[$value_field] = $pokemon[$db_field];
        }
        return $response;
    }
}
