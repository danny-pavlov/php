<?php

class User {
    private $table = 'users';

    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $password;
    public $email;
    public $phone;
    public $birthdate;
    public $organization;
    public $location;
    public $profile_image;
    public $created_at;
    public $updated_at;


    public $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function store(){
        $query = "INSERT INTO $this->table (username, password, email) VALUES (:username,:password,:email)";
        $stmt = $this->conn->prepare($query);
        $this->username = sanitize($this->username);
        $this->email = sanitize($this->email);
        $this->password = sanitize($this->password);
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashedPassword);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function login(){

        $query = "SELECT * FROM $this->table WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $this->email = sanitize($this->email);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $dbuser = $stmt->fetch(PDO::FETCH_OBJ);

        if($dbuser && password_verify($this->password, $dbuser->password)) {
            $this->id = $dbuser->id;
            $this->username = $dbuser->username;
            $this->first_name = $dbuser->first_name;
            $this->last_name = $dbuser->last_name;
            return true;
        }
        return false;
    }
}
