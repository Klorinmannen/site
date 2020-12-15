<?php
namespace make\svg;

class pie extends \make\svg\donut
{
    public function __construct(array $data = [])
    {
        parent::set_data($data);
    }
}
