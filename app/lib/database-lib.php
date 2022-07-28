<?php

class Database {
    private $hostname = 'localhost';
    private $password = '';
    private $username = 'root';
    private $dbname   = 'work_time';

    private $conn;

    function __construct() {
        $this->$conn = new mysqli($this->$hostname, $this->$username, $this->$password, $this->$dbname);


    }
}

?>