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
    private $_fill_color = 'rgb(255, 255, 200)';
    private $_stroke_colors = [ 'red', 'green', 'blue', 'purple', 'yellow', 'pink', 'orange', 'teal' ];

    private $_stroke_width = '3';
    private $_stroke_dasharray = '';
    private $_ws_thickness = 0.5;
    
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
    
    public function set_cx(int $cx) { $this->_cx = $cx; }
    public function set_cy(int $cy) { $this->_cy = $cy; }
    public function set_center(int $cx, int $cy)
    {
        $this->_cx = $cx;
        $this->_cy = $cy;
    }

    public function set_fill_color($color) { $this->_fill_color = $color; }
    public function set_stroke_width(int $width) { $this->_stroke_width = $width; }
    public function set_stroke_colors(array $colors) { $this->_stroke_colors = $colors; }
    public function set_ws_thickness(int $thickness) {$this->_ws_thickness = $thickness; }

    public function create()
    {
        $svg = sprintf('<svg width="%s" height="%s">', $this->_width, $this->_height);
        $svg .= '%s</svg>';

        $circles = [];
        $rotate = 0;
        $iteration = 0;
        foreach ($this->_data as $value) {
            $circles[] = self::get_circle($value, $rotate, $iteration);
            $rotate += self::get_rotation_part($value, $this->_total);
            $iteration++;
        }

        $g .= sprintf('<g transform-origin="%s %s" transform="rotate(-90)">%s</g>',
                       $this->_half_width,
                       $this->_half_height,
                       implode(' ', $circles));

        $this->_svg = sprintf($svg, $g);
        
        // Now what?
        if ($this->_echo_flag)
            echo $this->_svg;
        else
            return $this->_svg;
    }
    
    private function get_circle($value, $rotate, $iteration)
    {
        return sprintf('<circle transform-origin="%s %s" transform="rotate(%s)" cx="%d" cy="%d" r="%d" stroke="%s" fill="%s" stroke-width="%d" stroke-dasharray="%s,%s" />',
                       $this->_half_width,
                       $this->_half_height,
                       $rotate,
                       $this->_cx,
                       $this->_cy,
                       $this->_radius,
                       $this->_stroke_colors[$iteration],
                       $this->_fill_color,
                       $this->_stroke_width,
                       self::get_circle_part($value, $this->_total),
                       $this->_circumference);
    }
    
    private function get_rotation_part($value, $total)
    {
        return ($value / $total) * 360;
    }
    
    private function get_circle_part($value, $total)
    {
        $part = ($value / $total) * $this->_circumference;
        return count($this->_data) > 1 ? $part - 0.5 : $part;
    }
}
