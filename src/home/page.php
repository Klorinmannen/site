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
        $svg->set_size(1200, 1200);
        $svg->set_radius(400);
        $svg->set_stroke_width(200);
        $svg->set_spacing(10);
        $svg->set_fill_color('');
        $svg->set_data([400, 40, 65, 110, 600, 531]);
        $svg->set_bg_full();
        $html .= $svg->create();

        echo $html;
    }
}
