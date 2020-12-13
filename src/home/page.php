<?php
namespace home;

class page
{
    public static function default()
    {
        $html = 'Development site<br>';
        $html .= 'This is a home page<br>';
        $html .= sprintf('<img src="data:image/jpeg;base64,%s"/>',
                         base64_encode(file_get_contents(\site::DIR.'/data/spinda.jpg')));
        $html .= '<br><img style="text-align:center;" src="https://img.pokemondb.net/sprites/go/shiny/spinda.png" />';

        $svg = new \make\svg\donut();
        $svg->set_size(100, 100);
        $svg->set_radius(40);
        $svg->set_stroke_width(20);
        $svg->set_fill_color('gray');
        $svg->set_data([400, 10, 65, 110, 600, 531]);
        $svg->set_echo(false);
        $html .= $svg->create();

        echo $html;
    }
}
