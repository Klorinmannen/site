<?php
namespace util;

class table
{
    /*
      Needs a PDO to be set to function
      Fetch style needs to be set on the PDO.
      
      select fields can be either an array [ 'RealDatabaseFieldName', 'AnotherDatabaseFieldName' ]
      or a well formated sql string without a SELECT statement.

      where fields can be either an array [ 'RealDatabaseFieldName = SomeValue' ]
      or a well formated sql string without a WHERE statement.

      get with primary id will assume that the primary key field is $this->_table + ID
      i.e RealDatabaseTableNameID
      
     */
    
    public const DEFAULT_SELECT = '*';

    private $_table;
    private $_select;
    private $_where;
    private $_query;
    private $_pdo_fetch_mode;
    private $_pdo;
    private $_sql;
    private $_records;
    private $_fields;
    private $_params;
    private $_validator;

    // $table = RealDatabaseTableName
    public function __construct($table = null)
    {
        $this->_table = $table;
        $this->_pdo = null;
        $this->_select = null;
        $this->_where = null;
        $this->_query = null;
        $this->_sql = null;
        $this->_records = null;
        $this->_fields = null;
        $this->params = null;

        $this->_validator = new \Validator();
    }

    public function set_pdo($pdo)
    {
        $this->_pdo = $pdo;
    }

    public function set_select_fields($fields)
    {
        $this->_select = $fields;
    }

    public function set_where_fields($fields)
    {
        $this->_where = $fields;
    }

    // Returns records = [ 0  => [ 'RealDatabaseFieldName' => its value ] ]
    public function get(int $primary_id = null)
    {
        if ($this->_validator->validate_id($primary_id))
            $this->_primary_id = $primary_id;
        if (!$this->_pdo)
            throw new \Exception('Missing pdo');
        if (!$this->_table)
            throw new \Exception('Missing table');

        self::create_get_sql();
        self::make_query();
        self::set_records();

        return $this->_records;
    }

    // Assuming $fields =  [ 'RealDatabaseFieldName' => its value ]
    public function update(array $fields)
    {
        if (!$fields)
            throw new \Exception('Missing fields');

        self::create_update_fields_and_params($fields);
        self::create_update_sql($fields);
        self::make_query();

        return true;
    }

    // Assuming $fields = [ 'RealDatabaseFieldName' => its value ]
    public function insert(array $fields)
    {
        if (!$fields)
            throw new \Exception('Missing fields');

        self::create_insert_fields_and_params($fields);
        self::create_insert_sql($fields);
        self::make_query();

        return true;
    }
    
    private function create_update_fields_and_params($fields)
    {
        foreach ($fields as $db_field => $value) {
            $value_field = self::get_value_field($db_field);
            $this->_fields[] = sprintf('%s = :%s', $db_field, $value_field);
            $this->_params[$value_field] = $value;
        }
    }

    private function create_insert_fields_and_params($fields)
    {
        foreach ($fields as $db_field => $value) {
            $value_field = self::get_value_field($db_field);
            $this->_fields[] = $db_field;
            $this->_values[] = sprintf(':%s', $value_field);
            $this->_params[$value_field] = $value;
        }
    }

    // A need to normalize / generate a parameter name
    private function get_value_field($field)
    {
        $field = preg_replace('/[0-9]/', '', $field);
        return sprintf('value_%s_field', strtolower($field));
    }
        
    private function make_query()
    {
        if (!$this->_query = $this->_pdo->prepare($this->_sql))
            throw new \Exception('Failed to prepare query');
        if (!$this->_query->execute($this->_params))
            throw new \Exception('Failed to execute query');
    }

    private function set_records()
    {
        $this->_records = [];
        while ($record = $this->_query->fetch())
            $this->_records[] = $record;
    }

    private function create_update_sql($fields)
    {        
        $this->_sql = sprintf('UPDATE %s SET %s',
                              $this->_table,
                              implode(', ', $this->_fields));
        if ($this->_where)
            if (is_array($this->_where))
                $this->_sql .= sprintf(' WHERE %s',
                                       implode(' AND ', $this->_where));
            else
                $this->_sql .= sprintf(' WHERE %s',
                                       $this->_where);                                                       
    }

    private function create_insert_sql($fields)
    {
        $this->_sql = sprintf('INSERT INTO %s ( %s ) VALUES ( %S )',
                              $this->_table,
                              implode(', ', $this->_fields),
                              implode(', ', $this->_values));
    }
    
    private function create_get_sql()
    {
        $select = sprintf('SELECT %s', static::DEFAULT_SELECT);
        if ($this->_select)
            if (is_array($this->_select))
                $select = sprintf('SELECT %s', implode(', ', $this->_select));
            else
                $select = sprintf('SELECT %s', $this->_select);
        
        $from = sprintf(' FROM %s', $this->_table);

        $where_parts = [];
        if ($this->_where)
            if (is_array($this->_where))
                $where_parts[] = implode(' AND ', $this->_where);
            else
                $where_parts[] = $this->_where;
        
        if ($this->_primary_id)
            $where_parts[] = sprintf('%sID = %d', $this->_table, $this->_primary_id);

        $where = '';
        if ($where_parts)
            $where  = sprintf(' WHERE %s', implode(' AND ', $where_parts));

        $this->_sql = sprintf('%s %s %s', $select, $from, $where);
    }
}
