<?php
namespace util;

/*
  Needs a PDO to be set to function
  Fetch style needs to be set on the PDO.
      
  select fields can be either an array [ 'RealDatabaseFieldName', 'AnotherDatabaseFieldName' ]
  or a well formated sql string without a SELECT statement.

  where fields can be either an array [ 'RealDatabaseFieldName = SomeValue' ]
  or a well formated sql string without a WHERE statement.
      
*/
  
class table
{  
    public const DEFAULT_SELECT = '*';
    public const SELECT = 1;
    public const UPDATE = 2;
    public const INSERT = 3;

    private $_query_type;
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
    private $_where_fields;
    
    // $table = RealDatabaseTableName
    public function __construct($table = null)
    {
        $this->_query_type = null;
        $this->_table = $table;
        $this->_pdo = pdo();
        $this->_select = null;
        $this->_where = null;
        $this->_query = null;
        $this->_sql = null;
        $this->_records = null;
        $this->_fields = null;
        $this->params = null;
        $this->_where_fields = null;
    }

    public function set_pdo($pdo)
    {
        $this->_pdo = $pdo;
    }

    public function where($fields)
    {
        $this->_where_fields = $fields;
        self::set_where();
        return $this;
    }

    // Assuming $fields =  [ 'RealTableFieldName', 'AnotherRealTableFieldName' ]
    // Returns records = [ 0  => [ 'RealTableFieldName' => its value ], .. ]
    // Returns record = [ 'RealTableFieldName' => its value ]
    public function select($fields = null)
    {
        $this->_query_type = static::SELECT;
        self::create_select_fields_and_params($fields);
        return $this;
    }

    // Called to query the database/table after everything is 
    public function query()
    {

        switch ($this->_query_type) {
        case static::SELECT:
            self::create_select_sql();
            self::make_query();
            self::set_records();
            return $this->_records;

            break;
        case static::UPDATE:
            self::create_update_sql();
            self::make_query();
            return true;

            break;
        case static::INSERT:
            self::create_insert_sql();
            self::make_query();
            return $this->_pdo->lastInsertId();
            
            break;
        default:
            throw new \Exception('Unknown query type');
            break;
        }
    }
    
    // Assuming $fields =  [ 'RealTableFieldName' => its value ]
    public function update(array $fields)
    {
        if (!$fields)
            throw new \Exception('Missing fields');

        $this->_query_type = static::UPDATE;
        self::create_update_fields_and_params($fields);
        return $this;
    }

    // Assuming $fields = [ 'RealTableFieldName' => its value ]
    public function insert(array $fields)
    {
        if (!$fields)
            throw new \Exception('Missing fields');

        $this->_query_type = static::INSERT;
        self::create_insert_fields_and_params($fields);
        return $this;
    }
    
    public function get_records()
    {
        return $this->_records;
    }
    
    private function create_select_fields_and_params($fields)
    {
        $this->_select = static::DEFAULT_SELECT;
        if ($fields)
            if (is_array($fields))
                $this->_select = implode(', ', $fields);
            else
                $this->_select = $fields;
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
    
    private function set_where()
    {
        $where = '';
        if ($this->_where_fields) {
            $where = 'WHERE ';
            if (is_array($this->_where_fields)) {
                $field_parts = [];
                foreach ($this->_where_fields as $field => $value) {
                    $value_field = self::get_value_field($field);
                    $field_parts[] = sprintf('%s = :%s', $field, $value_field);                    
                    $this->_params[$value_field] = $value;
                }            
                $where .= implode(' AND ', $field_parts);
            } else
                $where .= $this->_where_fields;
        }        
        $this->_where = $where;
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
            throw new \Exception('Failed to prepare query', 500);
        if (!$this->_query->execute($this->_params))
            throw new \Exception('Failed to execute query', 500);
    }

    private function set_records()
    {
        $this->_records = [];
        while ($record = $this->_query->fetch())
            $this->_records[] = $record;            

        switch (count($this->_records)) {
        case 1:
            $this->_records = $this->_records[0];
            break;
        }
    }    
    
    private function create_update_sql()
    {        
        $this->_sql = sprintf( 'UPDATE %s SET %s %s',
                               $this->_table,
                               implode(', ', $this->_fields),
                               $this->_where );
        var_dump($this->_sql);
    }

    private function create_insert_sql()
    {
        $this->_sql = sprintf( 'INSERT INTO %s ( %s ) VALUES ( %s )',
                               $this->_table,
                               implode(', ', $this->_fields),
                               implode(', ', $this->_values) );
    }
    
    private function create_select_sql()
    {
        $this->_sql = sprintf( 'SELECT %s FROM %s %s',
                               $this->_select,
                               $this->_table,
                               $this->_where );
    }
}
