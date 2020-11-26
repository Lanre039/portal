<?php

class DatabaseObject {
    
    static protected $database;
    static protected $table_name = "";
    // static protected $columns = [];
    static protected $db_columns = [];
    public $errors = [];
    
    static public function set_database($connection) {
        self::$database = $connection;
    }
    
    static function find_by_sql($query) {
        $result = self::$database->query($query);
        if (!$result) {
            exit('Database query failed.' . self::$database->error);
        }
     
        $object_array = [];
        while($record = $result->fetch_assoc()) {
            $object_array[] = static::instantiate($record);
        }
        
        $result->free();
        
        return $object_array;
    }
    
    static protected function instantiate($record) {
        
        $object = new static;
        foreach($record as $property => $value) {
            if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }

    static public function find_all()
    {
        $query = "SELECT * FROM " . static::$table_name;
        return static::find_by_sql($query);
    }
    
    static public function count_all() {
        $query = "SELECT COUNT(*) FROM " . static::$table_name;
        $result_set = self::$database->query($query);
        $row = $result_set->fetch_array();
        return array_shift($row);
    }
    
    static public function find_by_id($id)
    {
        $query = "SELECT * FROM " . static::$table_name . " ";
        $query .= "WHERE id='" . self::$database->escape_string($id) . "'";
        $obj_array = static::find_by_sql($query);
        if(!empty($obj_array)) {
            return array_shift($obj_array); // array_shift returns the first element in the array
        } else {
            return false;
        }
    }
    
    static public function find_by_mentee_id($id)
    {
        $query = "SELECT * FROM " . static::$table_name . " ";
        $query .= "WHERE mentee_id='" . self::$database->escape_string($id) . "'";
        $obj_array = static::find_by_sql($query);
        if(!empty($obj_array)) {
            return $obj_array; // array_shift returns the first element in the array
        } else {
            return false;
        }
    }

    protected function create()
    {
        $this->validate();
        if (!empty($this->errors)) { return false; }
        
        $attributes = $this->sanitize_attribute();
        $query = "INSERT INTO " . static::$table_name . " (";
        $query .= join(', ', array_keys($attributes));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($attributes));
        $query .= "')";
        $result = self::$database->query($query);
        if ($result) {
            $this->id = self::$database->insert_id;
        }
        
        return $result;
    }
    
    protected function update()
    {
        $this->validate();
        if (!empty($this->errors)) { return false; }
        
        $data = [];
        $attributes = $this->sanitize_attribute();
        foreach ($attributes as $key => $value) {
            $data[] = "{$key}='{$value}'"; 
        }
        $query = "UPDATE " . static::$table_name . " SET ";
        $query .= join(', ', $data);
        $query .= " WHERE id='" . self::$database->escape_string($this->id) . "' ";
        $query .= "LIMIT 1";
        
        $result = self::$database->query($query);
        return $result;
    }
    public function delete()
    {
        $query = "DELETE FROM " . static::$table_name . " ";
        $query .= "WHERE id='" . self::$database->escape_string($this->id) . "'";
        $query .= "LIMIT 1";
        $result = self::$database->query($query);
        
        return $result;
    }
    
    public function save()
    {
        if (isset($this->id) && !empty($this->id)) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    protected function validate()
    {
        $this->errors = [];
        return $this->errors;
    }
    
    public function attributes()
    {
        $attributes = [];
        foreach(static::$db_columns as $column) {
            if ($column == 'id') { continue; }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }
    
    protected function sanitize_attribute()
    {
        $sanitized_attributes = [];
        foreach($this->attributes() as $key => $value) {
            $sanitized_attributes[$key] = self::$database->escape_string($value);
        }
        return $sanitized_attributes;
    }
    
    public function merge_attributes($args=[])             
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
    
?>