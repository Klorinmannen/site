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

        // Pie-chart
        $svg = new \make\svg\pie();
        $svg->set_size(700, 700);
        $svg->set_radius(100);
        $svg->set_stroke_width(200);
        $svg->set_spacing(0);
        $svg->set_data([400, 40, 65, 110, 600, 531, 111, 500, 1000, 712, 99, 453]);
        $html .= $svg->create();

        $svg = new \make\svg\pie();
        $svg->set_size(700, 700);
        $svg->set_radius(100);
        $svg->set_stroke_width(200);
        $svg->set_spacing(3);
        $svg->set_data([90, 180]);
        $html .= $svg->create();
        
        // Donut-chart
        $svg = new \make\svg\donut();
        $svg->set_size(700, 700);
        $svg->set_radius(200);
        $svg->set_stroke_width(100);
        $svg->set_spacing(0);
        $svg->set_fill_color('peachpuff');
        $svg->set_data([400, 40, 65, 110, 600, 531, 199, 343]);
        $html .= $svg->create();

        $svg = new \make\svg\donut();
        $svg->set_size(700, 700);
        $svg->set_radius(200);
        $svg->set_stroke_width(100);
        $svg->set_spacing(10);
        $svg->set_fill_color('moccasin');
        $svg->set_data([400, 40, 200]);
        $svg->set_bg_inside();
        $html .= $svg->create();
        
        echo $html;
    }
}
