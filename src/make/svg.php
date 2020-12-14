<?php
namespace make;

class svg
{
    // Assumption, $data = [ 'header' => value, 'header2' => value ]
    protected $_data = [];

    // Size
    protected $_width = 100;
    protected $_height = 100;
    protected $_half_height = 50;
    protected $_half_width = 50;

    protected $_echo_flag = true;
    protected $_svg = '';
    protected $_total = 0;    

    public function __construct()
    {

    }

    public function set_width(int $width)
    {
        $this->_width = $width;
        self::set_half();
    }

    public function set_height(int $height)
    {
        $this->_height = $height;
        self::set_half();
    }

    public function set_size(int $width, int $height)
    {
        $this->_height = $height;
        $this->_width = $width;
        self::set_half();
    }

    public function set_data(array $data)
    {
        $this->_data = $data;
        self::set_total();
    }

    private function set_half()
    {
        $this->_half_width = $this->_width * 0.5;
        $this->_half_height = $this->_height * 0.5;
    }        

    private function set_total()
    {
        foreach ($this->_data as $value)
            $this->_total += $value;
    }
}
