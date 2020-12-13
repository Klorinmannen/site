<?php

if (0) {
$arr1 = [2, 3];
$arr2 = [1, 4];

echo var_dump((bool)array_intersect($arr1, $arr2));
}

norm(250);
function norm($value, $max = 250, $min = 0) {
    echo (($value - $min) / ($max - $min))."\n";
}
