<?php
namespace make\svg;

class donut extends \make\svg
{
    // Center point of the circle
    private $_cx = 50;
    private $_cy = 50;

    // Circle radius 
    private $_radius = 50;
    private $_circumference;

    // Either rgb value or type, white, blue etc.
    private $_fill_color = '';
    private $_stroke_colors = [ 'red', 'green', 'blue', 'purple', 'yellow', 'pink', 'orange', 'teal' ];

    private $_stroke_width = '3';
    private $_stroke_dasharray = '';
    private $_ws = 0.5;
    private $_background_factor = 0.5;
    
    public function __construct(array $data = [])
    {
        parent::set_data($data);
        self::set_circumference();
    }

    public function set_radius(int $r)
    {
        $this->_radius = $r;
        self::set_circumference();
    }

    private function set_circumference()
    {
        $this->_circumference = $this->_radius * 2 * pi();
    }
    
    public function set_fill_color(string $color) { $this->_fill_color = $color; }
    public function set_stroke_width(int $width) { $this->_stroke_width = $width; }
    public function set_stroke_colors(array $colors) { $this->_stroke_colors = $colors; }
    public function set_spacing(float $spacing) {$this->_ws = $spacing; }
    public function set_bg_inside() { $this->_background_factor = 0.5; }
    public function set_bg_full() { $this->_background_factor = -0.5; }
    
    public function create()
    {
        $svg = sprintf('<svg width="%s" height="%s">', $this->_width, $this->_height);
        $svg .= '%s</svg>';

        $circles = [];
        $rotation = 0;
        $iteration = 0;
        
        if ($this->_fill_color)
            $circles[] = self::get_background_circle();
        
        foreach ($this->_data as $value) {
            $circles[] = self::get_circle($value, $rotation, $iteration);
            $rotation += self::get_rotation($value);
            $iteration++;
        }

        $g = sprintf('<g transform-origin="%s %s" transform="rotate(-90)">%s</g>',
                       $this->_half_width,
                       $this->_half_height,
                       implode(' ', $circles));

        $this->_svg = sprintf($svg, $g);
        
        return $this->_svg;
    }
    
    private function get_background_circle()
    {
        return sprintf('<circle transform-origin="%s %s" cx="%d" cy="%d" r="%d" fill="%s" />',
                       $this->_half_width,
                       $this->_half_height,
                       $this->_half_width,
                       $this->_half_height,
                       $this->_radius - ($this->_stroke_width * $this->_background_factor),
                       $this->_fill_color);
    }
    
    private function get_circle($value, $rotate, $iteration)
    {
        return sprintf('<circle fill-opacity="0" transform-origin="%s %s" transform="rotate(%s)" cx="%d" cy="%d" r="%d" stroke="%s" stroke-width="%d" stroke-dasharray="%s,%s" />',
                       $this->_half_width,
                       $this->_half_height,
                       $rotate,
                       $this->_half_width,
                       $this->_half_height,
                       $this->_radius,
                       $this->_stroke_colors[$iteration],
                       $this->_stroke_width,
                       self::get_circle_part($value),
                       $this->_circumference);
    }
    
    private function get_rotation($value)
    {
        return ($value / $this->_total) * 360;
    }
    
    private function get_circle_part($value)
    {
        $part = ($value / $this->_total) * $this->_circumference;
        return count($this->_data) > 1 ? $part - $this->_ws : $part;
    }
}
