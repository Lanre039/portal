<?php 

class Mentor extends DatabaseObject {
    
    static protected $table_name = 'mentor';
    static protected $db_columns = ['id', 'first_name', 'last_name', 'email', 'hashed_password'];
    
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    protected $hashed_password;
    
    public function __construct($args=[])
    {
        $this->first_name = $args['first_name'] ?? '';
        $this->last_name = $args['last_name'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }
    
    public function full_name()
    {
        return $this->first_name . " " . $this->last_name;
    }
    
    protected function set_hashed_password()
    {
        $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    
    public function verify_password($password)
    {
        return password_verify($password, $this->hashed_password);
    }
    
    protected function create()
    {
        $this->set_hashed_password();
        return parent::create();
    }
    
    protected function validate() {
    
        $this->errors = [];
      
        if(is_blank($this->first_name)) {
          $this->errors[] = "First name cannot be blank.";
        } elseif (!has_length($this->first_name, array('min' => 2, 'max' => 255))) {
          $this->errors[] = "First name must be between 2 and 255 characters.";
        }
      
        if(is_blank($this->last_name)) {
          $this->errors[] = "Last name cannot be blank.";
        } elseif (!has_length($this->last_name, array('min' => 2, 'max' => 255))) {
          $this->errors[] = "Last name must be between 2 and 255 characters.";
        }
      
        if(is_blank($this->email)) {
          $this->errors[] = "Email cannot be blank.";
        } elseif (!has_length($this->email, array('max' => 255))) {
          $this->errors[] = "Last name must be less than 255 characters.";
        } elseif (!has_valid_email_format($this->email)) {
          $this->errors[] = "Email must be a valid format.";
        }
      
      
        return $this->errors;
    }
    
    static public function find_by_email($email)
    {
        $query = "SELECT * FROM " . static::$table_name . " ";
        $query .= "WHERE email='" . self::$database->escape_string($email) . "'";
        $obj_array = static::find_by_sql($query);
        if(!empty($obj_array)) {
            return array_shift($obj_array); // array_shift returns the first element in the array
        } else {
            return false;
        }
    } 
}

?>