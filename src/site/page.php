<?php
namespace site;

class page
{
    public static function get_head_data()
    {
        $html = '<html>';
        $html .= '<head>';
        $html .= '<meta name="viewport" content="width=device-width,initial-scale=1.0">';
        $html .= '<link rel="stylesheet" href="/css/style.css">';
        $html .= '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
        $html .= '</head>';
        $html .= '<body>';
        return $html;
    }
    
    public static function default()
    {
        $html = static::get_head_data();
        
        $html .= '<div class="max_width">';
        
        $html .= '<div class="max_width no_margin no_padding text_center bg_color">';
        $html .= '<h2 class="no_margin_padding top">Pokemon GO statistic tool</h2>';
        $html .= '</div>';
       
		if ( user()->get_status() ) {
            
            $html .= '<form action="index.php" method="GET" class="no_padding no_margin">';
            $html .= '<div class="grid_center">';
            $html .= '<div class="grid_nav">';
                      
            $div = '<div>%s</div>';
            $opts = [ 'class' => 'button no_border border_radius',
                      'name' => 'page_id',
                      'value' => '4',
                      'title' => 'Logout' ];
            $button = \make\html::button($opts);
            $html .= sprintf($div, $button);

            $div = '<div>%s</div>';
            $opts = [ 'class' => 'button no_border border_radius',
                      'name' => 'page_id',
                      'value' => '11',
                      'title' => 'Pokémon' ];
            $button = \make\html::button($opts);
            $html .= sprintf($div, $button);

            $div = '<div>%s</div>';
            $opts = [ 'class' => 'button no_border border_radius',
                      'name' => 'page_id',
                      'value' => '12',
                      'title' => 'Stats' ];
            $button = \make\html::button($opts);
            $html .= sprintf($div, $button);
            
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</form>';                    
		}
        
		$html .= '<br>';
        $html .= '</div>';
        echo $html;
    }
}
