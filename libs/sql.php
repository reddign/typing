<?php
static $test = 1;
define("DBC", new SQLConnection("195.35.59.14", "u121755072_henryj2", "Jee22m88", "u121755072_henryj2db"));
// define("DBC", new SQLConnection("localhost", "root", "", "mca"));
class SQLConnection {
    private $conn;

    function __construct(string $server, string $uname, string $passwd, string $schema) {
        $this->conn = new mysqli($server, $uname, $passwd, $schema);
        if ($this->conn->connect_errno) {
            exit("SQL connection failed: $this->conn->connect_error");
        }
    }

    function query(string $sql) {
        $result = $this->conn->query($sql);
        if ($result instanceof mysqli_result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);     
        }
        return [];
    }

    function escape_string(string $str) {
        return mysqli_escape_string($this->conn, $str);
    }

    function __destruct() {
        $this->conn->close();
    }
}
?>