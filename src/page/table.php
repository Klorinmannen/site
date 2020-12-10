<?php
namespace page;

class table
{
    public static function get($page_id)
    {
        if (!$page_id)
            throw new \Exception('Missing page');

        $table = table('Page');
        $table->set_where_fields([ 'PageID' => $page_id, 'Active' => -1]);
        if (!$record = $table->select('Page'))
            throw new \Exception('Failed to get page');

        return $record['Page'];
    }
}
