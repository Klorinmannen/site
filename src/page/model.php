<?php
namespace page;

class model
{
    public static function get_by_id($page_id)
    {
        $record = table('Page')->select('Page')->where([ 'PageID' => $page_id, 'Active' => -1])->query();
        return $record['Page'];
    }
}
