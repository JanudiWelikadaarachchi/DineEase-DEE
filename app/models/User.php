<?php
class User {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    //find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        //bind value
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        //check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}