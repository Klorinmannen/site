<?php
namespace page;

class model
{
    public static function get_by_id($page_id)
    {
        $table = table('Page');
        $table->set_where_fields([ 'PageID' => $page_id, 'Active' => -1]);
        $record = $table->select('Page');
        return $record['Page'];
    }
}
