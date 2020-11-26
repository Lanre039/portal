<?php 

class MenteeMentor extends DatabaseObject {
    static protected $table_name = 'mentee_mentor';
    static protected $db_columns = ['id', 'mentor_id', 'mentee_id'];

    public $id;
    public $mentor_id;
    public $mentee_id;
    
    public function __construct($args=[])
    {
        $this->mentee_id = $args['mentee_id'] ?? '';
        $this->mentor_id = $args['mentor_id'] ?? '';
    }
    
    protected function create()
    {
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
    
    public function save()
    {
        if (isset($this->id) && !empty($this->id)) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    

}



?>