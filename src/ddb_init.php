<?php

class Database{

    private $host = "";
    private $database = "marvel_database";
    private $user = "";
    private $pass = "";
    private $db_connect;

    function __construct(){
        try
        {
            $this->db_connect = new PDO(
                "mysql:host=".$this->get_host().
                ";dbname=".$this->get_database(),
                 $this->get_user(),
                 $this->get_pass());
        }
        catch (exception $e)
        {
            die("Connection to database failed");
        }
    }

    function get_host(){
        return $this->host;
    }

    function get_database(){
        return $this->database;
    }

    function get_user(){
        return $this->user;
    }

    function get_pass(){
        return $this->pass;
    }

    function get_db_connect(){
        return $this->db_connect;
    }
};

?>
