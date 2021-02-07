<?php
namespace util;

class debug
{
    public static function sql(string $sql, $lb = "\n")
    {
        $output = preg_split('/(SELECT|FROM|WHERE|INSERT INTO|VALUES|INNER JOIN|ON|LEFT JOIN|RIGHT JOIN)/',
                             $sql,
                             -1,
                             \PREG_SPLIT_NO_EMPTY|\PREG_SPLIT_DELIM_CAPTURE);
        foreach ($output as $part)
            echo "$part $lb";
    }
}
