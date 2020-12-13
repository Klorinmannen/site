<?php
namespace util\make\svg;

class donut extends \util\make\svg
{
    // Center point of the circle
    private $_cx = 50;
    private $_cy = 50;

    // Circle radius 
    private $_radius = 50;

    private $_fill_color = 'red';
    private $_stroke_colors = [ 'header' => 'black' ];
    private $_stroke_width = '3';
    private $_stroke_dasharray = '';
    
    public function __construct(array $data = [])
    {
        parent::set_data($data);
    }

    public function set_radius(int $r) { $this->_radius = $r; }
    public function set_cx(int $cx) { $this->_cx = $cx; }
    public function set_cy(int $cy) { $this->_cy = $cy; }
    public function set_center(int $cx, int $cy) { $this->_cx = $cx; $this->_cy = $cy; }
    public function set_fill_color($color) { $this->_fill_color = $color; }
    public function set_stroke_width(int $width) { $this->_stroke_width = $width; }
    public function set_stroke_colors(array $colors) { $this->_stroke_colors = $colors; }

    public function create()
    {
        $svg = sprintf('<svg height="%d" width="%d" transform="rotate(-90)">',
                       $this->_height,
                       $this->_width);
        $svg .= '%s</svg>';
        $circles = [];
        foreach ($this->_data as $header => $value) {
            $circles[] = sprintf('<circle cx="%d" cy="%d" r="%d" stroke="%s" stroke-width="%d" fill="%s" stroke-dasharray="%s,%s" />',
                                 $this->_cx,
                                 $this->_cy,
                                 $this->_radius,
                                 $this->_stroke_colors[$header],
                                 $this->_stroke_width,
                                 $this->_fill_color,
                                 ($this->_radius * 2 * 3.14) * ($value/100),
                                 ($this->_radius * 2 * 3.14));
        }
        return sprintf($svg, implode(' ', $circles));
    }
}
