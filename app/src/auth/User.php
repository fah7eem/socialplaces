<?php
namespace Auth;
use System\Database;
use \Firebase\JWT\JWT;
use phpDocumentor\Reflection\Types\Integer;

class User extends Database{
    private $username;
    private $password;
    private $details;
    public function __construct(){
        parent::__construct();
    }

    public function setPassword($password)
    {
        $this->password = $this->clean_inputs($password);

        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $this->clean_inputs($username);

        return $this;
    }

    private function tokenDetails(array $row, int $seconds){
        return ["iat" => strtotime("now") ,"exp" => strtotime("+$seconds seconds"), "username" => $row['username']];
    }


    public function setDetails(){
        if(isset($_COOKIE['user'])){
            $user_cookie = $_COOKIE['user'];
            $this->details = JWT::decode($user_cookie, JWT_APP_SECRET, ["HS256"]);
        }else{
            $this->details = null;
        }
        return $this;
    }

    public function getDetails(){
        $this->setDetails();
        return $this->details;
    }

    public function authenticate(int $seconds){
        $row = $this->db->queryFirstRow("Select * from `user` where `username`=%s and `password`=SHA2(concat(%s, `username`), 256);" , $this->username, $this->password);
        return $row === null ? false : JWT::encode( $this->tokenDetails($row, $seconds), JWT_APP_SECRET, "HS256");
    }

}