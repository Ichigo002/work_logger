<?php

class Database {
    private $hostname = 'localhost';
    private $password = '';
    private $username = 'root';
    private $dbname   = 'work_time';

    private $conn;
    private $result = null;

    public function __construct() {
        $this->connectdb();
    }

    // Change current connected database
    public function change_db($host, $password, $username, $dbname) {
        $this->hostname = $host;
        $this->password = $password;
        $this->username = $username;
        $this->dbname = $dbname;

        $this->connectdb();
    }

    // Call sql query to db
    public function query($sql) {
        $this->result = $this->conn->query($sql);
        return $this->result;
    }


    // Check has result got any data
    public function check_result()
    {
       return $this->result->num_rows > 0;
    }

    // Get signle row. The best solution is with 
    // while($row = $db->get_single_row()) { #code... }
    public function get_single_row() {
        return $this->result->fetch_assoc();
    }

    // Get column of result.
    public function count_table($name_of_table, $condition_sql="") {
        //$sql = "SELECT COUNT(*) FROM `$name_of_table` WHERE $condition_sql";
        $sql = "SELECT COUNT(*) FROM `$name_of_table` WHERE ".$condition_sql.";";
        return $this->query($sql)->fetch_column();
    }

    protected function connectdb() {
        try {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
        } catch (\Throwable $th) {
            echo "Failed to connect to MySQL Database";
            exit();
        }
    }
}

?>