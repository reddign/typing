<?php
$ini_array = parse_ini_file("dbcreds.ini");
$connection = new SQLConnection($ini_array['ip'], $ini_array['username'], $ini_array['password'], $ini_array['schema']);
unset($ini_array);
class SQLConnection {
    private $conn;

    function __construct(string $server, string $uname, string $passwd, string $schema) {
        $this->conn = new mysqli($server, $uname, $passwd, $schema);
        if ($this->conn->connect_errno) {
            exit("SQL connection failed: $this->conn->connect_error");
        }
    }

    /**
     * Runs a SQL query
     * @return array|bool if the query returns results, an array is returned, otherwise, a boolean representing
     * the success of the operation is returned
     */
    function query(string $sql): array | bool {
        $result = $this->conn->query($sql);
        if ($result instanceof mysqli_result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);     
        }
        return $result;
    }

    function escape_string(string $str) {
        return mysqli_escape_string($this->conn, $str);
    }

    function __destruct() {
        $this->conn->close();
    }
}
?>