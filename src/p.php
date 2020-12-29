<?php
require_once('/var/www/site/src/init.php');

error_reporting(-1);

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_names.json';
    $result = \util\curl::execute_get_call($url);
    $json_string = \util\json::decode($result);
    foreach ($json_string as $file_pokemon) {
        if (! \pokemon\model::get_by_id($file_pokemon['id'])) {
            $pokemon = [ 'PokemonID' => $file_pokemon['id'], 
                         'Name' => $file_pokemon['name'],
                         'DexID' => sprintf('#%03d', $file_pokemon['id']) ];
            \pokemon\model::insert($pokemon);
        }
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/shiny_pokemon.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $id => $shiny_pokemon) {
        $pokemon = [ 'Shiny' => -1 ];
        \pokemon\model::update_by_id($id, $pokemon);
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/shadow_pokemon.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $id => $shiny_pokemon) {       
        $pokemon = [ 'Shadow' => -1 ];
        \pokemon\model::update_by_id($id, $pokemon);
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_evolutions.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $id => $curr_pokemon) {
        $evos = $curr_pokemon['evolutions'];
        $family_ids = [];
        foreach ($evos as $evolved_pokemon){
            $family_ids[] = $evolved_pokemon['pokemon_id'];
        }
        $pokemon = [ 'ParentPokemonIDList' => implode(',', $family_ids) ];
        \pokemon\model::update_by_id($curr_pokemon['pokemon_id'], $pokemon);
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_stats.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result, true);
    foreach ($file as $id => $api_pokemon) {
        $pokemon = [ 'Stamina' => $api_pokemon['base_stamina'],
                     'Attack' => $api_pokemon['base_attack'],
                     'Defense' => $api_pokemon['base_defense'] ];
        \pokemon\model::update_by_id($api_pokemon['pokemon_id'], $pokemon);
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_rarity.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $rarities) {
        foreach ($rarities as $api_pokemon) {
            $rarity = \pokemon\rarity\model::get_by_rarity($api_pokemon['rarity']);
            $pokemon = [ 'PokemonRarityID' => $rarity['PokemonRarityID'] ];            
            \pokemon\model::update_by_id($api_pokemon['pokemon_id'], $pokemon);
        }
    }
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_types.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $api_pokemon) {
        $types = $api_pokemon['type'];
        $pokemon_type_ids = [];
        foreach ($types as $type) {
            $pdo_type = \pokemon\type\model::get_by_type($type);          
            $pokemon_type_ids[] = $pdo_type['PokemonTypeID'];
        }
        $pokemon['PokemonTypeIDList'] = implode(',', $pokemon_type_ids);
        \pokemon\model::update_by_id($api_pokemon['pokemon_id'], $pokemon);
    }
}

if (false)
{

    $url = 'https://pogoapi.net/api/v1/raid_bosses.json';
    $result = \curl::execute_get_call($url);
    $file = json_decode($result, true);
    //echo print_r($file);
    
    $current = $file['current'];
    foreach ($current as $tier => $tier_bosses) {
        foreach ($tier_bosses as $tier_boss) {

            if (!$form_id = \pokemon\form\action::get_id_by_form($tier_boss['form']))
                $form_id = NULL;
            $tier_id = \pokemon\boss\tier\action::get_id_by_tier($tier_boss['tier']);
            $boss = [ 'pokemon_id' => $tier_boss['id'],
                      'form_id' => $form_id,
                      'max_cp' => $tier_boss['max_unboosted_cp'],
                      'min_cp' => $tier_boss['min_unboosted_cp'],
                      'boosted_max_cp' => $tier_boss['max_boosted_cp'],
                      'boosted_min_cp' => $tier_boss['min_boosted_cp'],
                      'tier_id' => $tier_id,
                      'active' => -1 ];
            
            \pokemon\boss\action::insert($boss);
        }
    }        

    $current = $file['previous'];
    foreach ($current as $tier => $tier_bosses) {
        foreach ($tier_bosses as $tier_boss) {

            if (! $form_id = \pokemon\form\action::get_id_by_form($tier_boss['form']))
                $form_id = NULL;
            $tier_id = \pokemon\boss\tier\action::get_id_by_tier($tier_boss['tier']);
            $boss = [ 'pokemon_id' => $tier_boss['id'],
                      'form_id' => $form_id,
                      'max_cp' => $tier_boss['max_unboosted_cp'],
                      'min_cp' => $tier_boss['min_unboosted_cp'],
                      'boosted_max_cp' => $tier_boss['max_boosted_cp'],
                      'boosted_min_cp' => $tier_boss['min_boosted_cp'],
                      'tier_id' => $tier_id,
                      'active' => 0 ];
            
            \pokemon\boss\action::insert($boss);
        }
    }        
}

if (false)
{

    $url = 'https://pogoapi.net/api/v1/raid_bosses.json';
    $result = \curl::execute_get_call($url);
    $file = json_decode($result, true);
    //echo print_r($file);
    $current = $file['current'];
    //$current = $file['previous'];
    foreach ($current as $tier => $tier_bosses) {
        foreach ($tier_bosses as $tier_boss) {
            $id = $tier_boss['id'];
            $boss = [ 'active' => -1 ];
            
            \pokemon\boss\action::update_by_pokemon_id($id, $boss);
        }
    }        
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/pokemon_forms.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $form)
        \pokemon\form\model::insert($form);    
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/alolan_pokemon.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result, true);
    foreach ($file as $id => $pokemon)
        \pokemon\alolan\model::insert($pokemon);
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/galarian_pokemon.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $id => $pokemon)
        \pokemon\galarian\action::insert($pokemon);
}

if (false)
{
    $url = 'https://pogoapi.net/api/v1/type_effectiveness.json';
    $result = \util\curl::execute_get_call($url);
    $file = \util\json::decode($result);
    foreach ($file as $current_type => $types) {
        $pdo_current_type = \pokemon\type\model::get_by_type($current_type);
        $type = [ 'pokemon_type_id' => $pdo_current_type['PokemonTypeID'] ];
        foreach ($types as $against_type => $multiplier) {
            $pdo_against_type = \pokemon\type\model::get_by_type($against_type);
            $type['defending'] = [ 'pokemon_type_id' =>  $pdo_against_type['PokemonTypeID'],
                                   'multiplier' => $multiplier ];
            \pokemon\type\effectiveness\model::insert($type);
        }
    }
}



